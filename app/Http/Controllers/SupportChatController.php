<?php

namespace App\Http\Controllers;

use App\Events\SupportChat\MessageEvent;
use App\Http\Requests\SupportChat\SupportChatsListRequest;
use App\Http\Requests\SupportChat\SupporterNewMessageRequest;
use App\Http\Requests\SupportChat\SupporterRequest;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SupportChatController extends Controller
{
  public function userGetMessages(Request $request)
  {
    $user = auth()->user();

    $supportChat = $user->supportChat;
    if (!$supportChat) {
      $supportChat = SupportChat::create([
        'user_id' => $user->id
      ]);
    }

    $messages = SupportChat::chatHistory(
      $supportChat->id
    )->paginate($request->get('per_page') ?? 10);

    return response($messages, 200);
  }

  public function userWriteMessage(Request $request)
  {
    throw_if(!$request->message, new BadRequestHttpException());

    $user = auth()->user();
    $message = SupportChatMessage::create([
      'chat_id' => $user->supportChat->id,
      'message_author' => $user->id,
      'message_text' => strip_tags($request->message)
    ]);
    $message->by_user = true;
    $messageAttrs = $message->only([
      'id',
      'message_text',
      'by_user',
      'created_at',
      'updated_at'
    ]);

    $chat = SupportChat::where('user_id', $user->id)->first();
    MessageEvent::dispatch(User::find($chat->user_id), $message, $chat);

    return response([
      'ok' => true,
      'data' => [
        'message' => $messageAttrs
      ]
    ], 201);
  }

  public function supporterGetMessages(SupporterRequest $request)
  {
    $messages = SupportChat::chatHistory(
      $request->chat_id
    )->paginate($request->get('per_page') ?? 10);

    return response($messages, 200);
  }

  public function supporterWriteMessage(SupporterNewMessageRequest $request)
  {
    $message = SupportChatMessage::create([
      'chat_id' => $request->chat_id,
      'message_author' => auth()->user()->id,
      'message_text' => strip_tags($request->message)
    ]);
    $message->by_user = false;
    $messageAttrs = $message->only([
      'id',
      'message_text',
      'by_user',
      'created_at',
      'updated_at'
    ]);

    $chat = SupportChat::find($request->chat_id);
    MessageEvent::dispatch(User::find($chat->user_id), $message, $chat);

    return response([
      'ok' => true,
      'data' => [
        'message' => $messageAttrs
      ]
    ], 201);
  }

  public function supporterGetChatsList(SupportChatsListRequest $request)
  {
    $chats = SupportChat::chatsList()
      ->paginate($request->per_page ?? 10);

    return response($chats, 200);
  }

  public function currentUserChat()
  {
    return response([
      'data' => [
        'chat_id' => auth()->user()->supportChat->id
      ]
    ], 200);
  }
}

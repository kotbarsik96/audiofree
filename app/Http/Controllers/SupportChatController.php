<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportChat\SupportChatsListRequest;
use App\Http\Requests\SupportChat\SupporterNewMessageRequest;
use App\Http\Requests\SupportChat\SupporterRequest;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
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

    $message = SupportChatMessage::create([
      'chat_id' => auth()->user()->supportChat->id,
      'message_author' => auth()->user()->id,
      'message_text' => strip_tags($request->message)
    ]);
    $message->by_user = true;
    $message = $message->only([
      'id',
      'message_text',
      'by_user',
      'created_at',
      'updated_at'
    ]);

    return response([
      'ok' => true,
      'data' => [
        'message' => $message
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
    $message = $message->only([
      'id',
      'message_text',
      'by_user',
      'created_at',
      'updated_at'
    ]);

    return response([
      'ok' => true,
      'data' => [
        'message' => $message
      ]
    ], 201);
  }

  public function supporterGetChatsList(SupportChatsListRequest $request)
  {
    $chats = SupportChat::chatsList()
      ->paginate($request->per_page ?? 10);

    return response($chats, 200);
  }
}

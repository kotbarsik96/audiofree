<?php

namespace App\Http\Controllers;

use App\Events\SupportChat\MessageEvent;
use App\Events\SupportChat\MessageReadEvent;
use App\Http\Requests\SupportChat\SupportChatReadRequest;
use App\Http\Requests\SupportChat\SupportChatRequest;
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
    $perPage = intval($request->get('per_page') ?? 10);
    $messages = null;

    if (!$request->get('page')) {
      $firstUnreadMessage = SupportChatMessage::unreadMessages($supportChat)
        ->orderBy('created_at')
        ->first();

      if ($firstUnreadMessage) {
        $messages = SupportChatMessage::fromFirstUnreadMessage($supportChat, $firstUnreadMessage, $perPage);
      }
    }

    if (!$messages) {
      $messages = SupportChat::chatHistory(
        $supportChat->id
      )->paginate(perPage: $perPage);
    }

    return response($messages, 200);
  }

  public function userWriteMessage(Request $request)
  {
    throw_if(!(str($request->message)), new BadRequestHttpException());

    $user = auth()->user();
    $message = SupportChatMessage::create([
      'chat_id' => $user->supportChat->id,
      'message_author' => $user->id,
      'message_text' => strip_tags($request->message),
      'was_read' => false
    ]);
    $message->by_user = true;
    $messageAttrs = $message->attrsToFront();

    $chat = SupportChat::where('user_id', $user->id)->first();
    MessageEvent::broadcast(User::find($chat->user_id), $message, $chat)->toOthers();

    return response([
      'ok' => true,
      'data' => [
        'message' => $messageAttrs
      ]
    ], 201);
  }

  public function supporterGetMessages(SupporterRequest $request)
  {
    $supportChat = SupportChat::find($request->chat_id);
    $perPage = intval($request->get('per_page') ?? 10);
    $messages = null;

    if (!$request->get('page')) {
      $firstUnreadMessage = SupportChatMessage::unreadMessages($supportChat)
        ->orderBy('created_at')
        ->first();

      if ($firstUnreadMessage) {
        $messages = SupportChatMessage::fromFirstUnreadMessage($supportChat, $firstUnreadMessage, $perPage);
      }
    }

    if (!$messages) {
      $messages = SupportChat::chatHistory(
        $supportChat->id
      )->paginate(perPage: $perPage);
    }

    return response($messages, 200);
  }

  public function supporterWriteMessage(SupporterNewMessageRequest $request)
  {
    $message = SupportChatMessage::create([
      'chat_id' => $request->chat_id,
      'message_author' => auth()->user()->id,
      'message_text' => strip_tags($request->message),
      'was_read' => false
    ]);
    $message->by_user = false;
    $messageAttrs = $message->attrsToFront();

    $chat = SupportChat::find($request->chat_id);
    MessageEvent::broadcast(User::find($chat->user_id), $message, $chat)->toOthers();

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

  public function chatInfo(SupportChatRequest $request)
  {
    $chatId = $request->get('chat_id');
    $chat = $chatId ? SupportChat::find($chatId) : auth()->user()->supportChat;

    return response([
      'data' => [
        'chat_id' => $chat->id,
        'unread_messages_count' =>
          SupportChatMessage::unreadMessages($chat)->count()
      ]
    ], 200);
  }

  public function read(SupportChatReadRequest $request)
  {
    SupportChatMessage::whereIn('id', $request->read_messages_ids)
      ->update(['was_read' => true]);

    MessageReadEvent::broadcast($request->read_messages_ids, $request->chat)->toOthers();

    return response([
      'ok' => true,
      'data' => [
        'read_messages' => $request->read_messages_ids
      ]
    ], 200);
  }
}

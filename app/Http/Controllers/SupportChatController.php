<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportChat\SupporterNewMessageRequest;
use App\Http\Requests\SupportChat\SupporterRequest;
use App\Models\SupportChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SupportChatController extends Controller
{
  public function userGetMessages(Request $request)
  {
    $messages = SupportChat::chatHistory(
      auth()->user()->id
    )->paginate($request->get('per_page') ?? 10);

    return response($messages, 200);
  }

  public function userWriteMessage(Request $request)
  {
    throw_if(!$request->message, new BadRequestHttpException());

    $message = SupportChat::create([
      'user_id' => auth()->user()->id,
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

  public function getMessagesAsSupporter(SupporterRequest $request)
  {
    $messages = SupportChat::chatHistory(
      $request->chat_user_id
    )->paginate($request->get('per_page') ?? 10);

    return response($messages, 200);
  }

  public function writeMessageAsSupporter(SupporterNewMessageRequest $request)
  {
    $message = SupportChat::create([
      'user_id' => $request->chat_user_id,
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
}

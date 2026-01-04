<?php

namespace App\Services\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Events\SupportChat\SupportChatReadEvent;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use Carbon\Carbon;

class SupportChatService
{
  public function __construct()
  {
  }

  public function writeMessage(SupportChat $chat, SupportChatSenderTypeEnum $senderType, string $text)
  {
    $user = auth()->user();
    $chat->setOpenStatus();

    $updatedIds = $chat->unreadMessagesFromCompanion($senderType)
      ->select('id')
      ->get()
      ->pluck('id');
    $chat->unreadMessagesFromCompanion($senderType)
      ->update([
        'read_at' => Carbon::now()
      ]);

    SupportChatReadEvent::dispatch($updatedIds, $chat, auth()->user());

    return SupportChatMessage::create([
      'chat_id' => $chat->id,
      'author_id' => $user->id,
      'sender_type' => $senderType,
      'text' => $text,
    ]);
  }
}
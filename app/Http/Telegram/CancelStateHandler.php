<?php

namespace App\Http\Telegram;

use App\DTO\Enums\ConfirmationPurposeEnum;
use App\Models\Confirmation;
use App\Models\Telegram\TelegraphChat;
use DefStudio\Telegraph\DTO\Message;

/**
 * Методы класса используются при обнулении TelegraphChat->state (т.е. при вызове Handler->cancelState())
 * 
 * Нужны для реализации дополнительной логики, предешствующей обнулению state (например, убрать код подтверждения из БД)
 * 
 */
class CancelStateHandler
{
  public function __construct(
    public TelegraphChat $chat
  ) {
  }

  public function connectProfile()
  {
    $purpose = ConfirmationPurposeEnum::CONNECT_TELEGRAM;
    $data = $this->chat->data ?? [];
    $userId = array_key_exists('user_id', $data) ? $data['user_id'] : null;
    if ($userId) {
      Confirmation::where('user_id', $userId)
        ->where('purpose', $purpose)
        ->delete();
    }
  }

  public function connectProfileEnterCode()
  {
    return $this->connectProfile();
  }
}
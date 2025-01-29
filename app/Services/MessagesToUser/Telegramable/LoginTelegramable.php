<?php

namespace App\Services\MessagesToUser\Telegramable;

use App\Models\User;
use DefStudio\Telegraph\Facades\Telegraph;

class LoginTelegramable extends Telegramable
{
  public function __construct(public string $code)
  {
  }

  public function send(User $user)
  {
    Telegraph::chat($user->telegram_chat_id)
      ->html(__('telegram.auth.code', ['code' => $this->code]))
      ->send();
  }
}
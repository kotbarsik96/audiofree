<?php

namespace App\Services\MessagesToUser\Telegramable;

use App\Models\User;
use DefStudio\Telegraph\Facades\Telegraph;

class LoginTelegramable extends Telegramable
{
  public function send()
  {
    $this->user->telegramChat
      ->html(__('telegram.auth.code', ['code' => $this->code]))
      ->send();
  }
}
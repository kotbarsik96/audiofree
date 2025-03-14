<?php

namespace App\Services\MessagesToUser\Telegramable;

use App\Models\User;
use App\Services\StringsService;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class ResetPasswordTelegramable extends Telegramable
{
  public function send()
  {
    $link = StringsService::resetLink($this->code, $this->user->telegram);

    $this->user->telegramChat
      ->html(__('telegram.auth.resetPasswordRequested'))
      ->keyboard(Keyboard::make()->buttons([
        Button::make(__('telegram.auth.resetPassword'))->url($link)
      ]))
      ->send();
  }
}
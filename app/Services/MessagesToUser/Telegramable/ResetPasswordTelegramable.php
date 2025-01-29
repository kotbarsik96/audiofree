<?php

namespace App\Services\MessagesToUser\Telegramable;

use App\Models\User;
use App\Services\StringsService;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class ResetPasswordTelegramable extends Telegramable
{
  public function __construct(public string $code)
  {
  }

  public function send(User $user)
  {
    $link = StringsService::resetLink($this->code, $user->telegram);

    Telegraph::chat($user->telegram_chat_id)
      ->html(__('telegram.auth.resetPasswordRequested'))
      ->keyboard(Keyboard::make()->buttons([
        Button::make(__('telegram.auth.resetPassword'))->url($link)
      ]))
      ->send();
  }
}
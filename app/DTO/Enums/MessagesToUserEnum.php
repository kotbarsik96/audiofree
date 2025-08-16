<?php

namespace App\DTO\Enums;

use App\DTO\MessagesToUserDTO;
use App\Services\MessagesToUser\Telegramable\Telegramable;
use Illuminate\Mail\Mailable;

enum MessagesToUserEnum: string
{
  case TELEGRAM = 'Telegram';
  case EMAIL = 'Email';

  public function dto()
  {
    /** При добавлении новых DTO не забывать:
     * обновлять MTUController->send
     */
    return match ($this) {
      MessagesToUserEnum::TELEGRAM => new MessagesToUserDTO(
        Telegramable::class
      ),

      MessagesToUserEnum::EMAIL => new MessagesToUserDTO(
        Mailable::class
      )
    };
  }
}
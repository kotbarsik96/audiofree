<?php

namespace App\DTO\MessagesToUser;

use App\DTO\MessagesToUser\MessagesToUserDTO;
use App\DTO\DTOCollection;
use App\Enums\MessagesToUserEnum;
use App\Services\MessagesToUser\Telegramable\Telegramable;
use Illuminate\Mail\Mailable;

/**
 * @extends DTOCollection<MessagesToUserDTO>
 */
class MessagesToUserDTOCollection extends DTOCollection
{
  public static $enum = MessagesToUserEnum;

  /** При добавлении новых DTO не забывать:
   * обновлять MTUController->send
   */
  public static function getDTO($key)
  {
    return match ($key) {
      MessagesToUserEnum::TELEGRAM => new MessagesToUserDTO(
        MessagesToUserEnum::TELEGRAM,
        Telegramable::class
      ),
      MessagesToUserEnum::EMAIL => new MessagesToUserDTO(
        MessagesToUserEnum::EMAIL,
        Mailable::class
      )
    };
  }
}

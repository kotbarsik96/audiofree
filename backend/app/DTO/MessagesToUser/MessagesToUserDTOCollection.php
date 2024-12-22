<?php

namespace App\DTO\MessagesToUser;

use App\DTO\MessagesToUser\MessagesToUserDTO;
use App\DTO\DTOCollection;
use App\Services\MessagesToUser\Telegramable\Telegramable;
use Illuminate\Mail\Mailable;

MessagesToUserDTOCollection::register(
  'Telegram',
  new MessagesToUserDTO('Telegram', Telegramable::class)
);

MessagesToUserDTOCollection::register(
  'Email',
  new MessagesToUserDTO('Telegram', Mailable::class)
);

/**
 * @extends DTOCollection<MessagesToUserDTO>
 */
class MessagesToUserDTOCollection extends DTOCollection
{
}
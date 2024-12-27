<?php

namespace App\DTO\MessagesToUser;

use App\DTO\MessagesToUser\MessagesToUserDTO;
use App\DTO\DTOCollection;
use App\Services\MessagesToUser\Telegramable\Telegramable;
use Illuminate\Mail\Mailable;

/**
 * @extends DTOCollection<MessagesToUserDTO>
 */
class MessagesToUserDTOCollection extends DTOCollection
{
}

/** При добавлении новых DTO не забывать:
 * обновлять MTUController->send
 */
MessagesToUserDTOCollection::register(
  'Telegram',
  new MessagesToUserDTO('Telegram', Telegramable::class)
);

MessagesToUserDTOCollection::register(
  'Email',
  new MessagesToUserDTO('Email', Mailable::class)
);
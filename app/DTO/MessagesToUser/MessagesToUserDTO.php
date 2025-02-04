<?php

namespace App\DTO\MessagesToUser;

use App\Enums\MessagesToUserEnum;

class MessagesToUserDTO
{
  public function __construct(
    public MessagesToUserEnum $sendTo, // 'Email', 'Telegram', ...
    public $ableClass, // Mailable, Telegramable, ...
  ) {
  }
}
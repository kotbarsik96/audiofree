<?php

namespace App\DTO\MessagesToUser;

class MessagesToUserDTO
{
  public function __construct(
    public string $sendTo, // 'Email', 'Telegram', ...
    public $ableClass, // Mailable, Telegramable, ...
  ) {
  }
}
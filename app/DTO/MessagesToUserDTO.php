<?php

namespace App\DTO;

class MessagesToUserDTO
{
  public function __construct(
    public $ableClass, // Mailable, Telegramable, ...
  ) {
  }
}
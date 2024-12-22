<?php

namespace App\DTO\MessagesToUser;

class MessagesToUserDTO
{
  private function __construct(
    public string $sendTo,
    public $ableClass,
  ) {
  }
}
<?php

namespace App\Services\MessagesToUser\Telegramable;

use App\Services\Interfaces\TelegramableInterface;
use App\Models\User;

class LoginTelegramable implements TelegramableInterface
{
  public function __construct(public string $code)
  {
  }

  public function send(User $user)
  {
    
  }
}
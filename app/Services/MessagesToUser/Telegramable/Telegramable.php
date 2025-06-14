<?php

namespace App\Services\MessagesToUser\Telegramable;

use App\Models\User;
use DefStudio\Telegraph\Facades\Telegraph;

class Telegramable
{
  protected ?string $code = null;

  public function __construct(
    public User $user
  ) {
  }

  public function setCode(string $code)
  {
    $this->code = $code;
  }

  public function send()
  {
    $this->user->telegramChat->message('')->send();
  }
}
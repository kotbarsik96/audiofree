<?php

namespace App\Services\MessagesToUser\Mailable;

use App\Models\User;
use Illuminate\Mail\Mailable;

class MailableCustom extends Mailable
{
  protected string $code;

  public function __construct(public User $user)
  {
  }

  public function setCode(string $code)
  {
    $this->code = $code;
  }
}
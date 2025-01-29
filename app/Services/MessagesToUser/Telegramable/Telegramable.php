<?php

namespace App\Services\MessagesToUser\Telegramable;

use App\Models\User;
use DefStudio\Telegraph\Facades\Telegraph;

class Telegramable
{
  public function send(User $user)
  {
    Telegraph::message('')->chat($user->telegram_chat_id)->send();
  }
}
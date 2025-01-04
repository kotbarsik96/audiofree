<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface TelegramableInterface
{
  /**
   * должен отсылать сообщение, используя Telegram\Bot\Laravel\Facades\Telegram;
   * @return void
   */
  public function send(User $user);
}
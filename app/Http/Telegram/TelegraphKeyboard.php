<?php

namespace App\Http\Telegram;

use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Keyboard\Keyboard;
use App\Models\User;

class TelegraphKeyboard
{
  public static function onMessage(Message $message, User|null $user = null)
  {
    return Keyboard::make()
      ->when(
        !$user,
        fn(Keyboard $keyboard) =>
        $keyboard->button(__('telegram.connectProfile.title'))
          ->action('connectProfile')
      )
      ->when(
        !$user,
        fn(Keyboard $keyboard) =>
        $keyboard->button(__('telegram.button.register'))->action('register')
          ->param('firstname', $message->from()->firstName())
          ->param('username', $message->from()->username())
      );
  }

  public static function cancelState()
  {

  }
}
<?php

namespace App\Http\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Models\TelegraphChat;
use App\Models\User;
use DefStudio\Telegraph\DTO\Message;
use Illuminate\Support\Stringable;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

/** используется для вспомогательных действий, чтобы не захламлять Handler.php */
class HandlerActions
{
  public static function onMessageOrStartCommand(TelegraphChat $chat, Message $message)
  {
    $user = User::where('telegram', $message->from()->username())->first();

    $chat->message(__('telegram.welcome.general'))
      ->keyboard(Keyboard::make()
        ->when(
          !$user,
          fn(Keyboard $keyboard) =>
          $keyboard->button(__('telegram.button.register'))->action('register')
            ->param('firstname', $message->from()->firstName())
            ->param('username', $message->from()->username())
        ))
      ->send();
  }
}
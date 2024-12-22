<?php

namespace App\Services\MessagesToUser;

use App\DTO\MessagesToUser\MessagesToUserDTOCollection;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Mail;

class MTUController
{
  public User $user;

  public array $channels;

  public function __construct()
  {
    $this->user = User::find(auth()->user()->id);
    $this->channels = $this->defineUserDesiredChannels();
  }

  public function defineUserDesiredChannels(): array
  {
    return ['Email']; // в дальнейшем делать проверку на наличие поля 'Email' или 'Telegram' или обоих сразу
  }

  public function isDesired(string $key): bool
  {
    return in_array($key, $this->channels);
  }

  /**
   * Определяет предпочтительные пользователю каналы связи и отправляет по ним сообщения
   * @param $ables - экземпляры Mailable/Telegramable
   * @return array<string> - каналы связи, куда было отправлено собщение (например, ['Telegram', 'Email'])
   */
  public function send(...$ables): array
  {
    $sentToArr = [];

    foreach ($ables as $able) {
      if ($able instanceof Mailable && $this->isDesired('Email')) {
        Mail::to(auth()->user())->send($able);
      }
      if ($able instanceof Telegramable && $this->isDesired('Telegram')) {
        // 
      }
    }

    return $sentToArr;
  }
}
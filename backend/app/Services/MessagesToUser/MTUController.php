<?php

namespace App\Services\MessagesToUser;

use App\DTO\MessagesToUser\MessagesToUserDTOCollection;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Mail;

class MTUController
{
  public array $channels = [];

  public function __construct(public User $user)
  {
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
        Mail::to($this->user)->send($able);
        $sentToArr[] = 'Email';
      }
      if ($able instanceof Telegramable && $this->isDesired('Telegram')) {
        $sentToArr[] = 'Telegram';
      }
    }

    return $sentToArr;
  }
}
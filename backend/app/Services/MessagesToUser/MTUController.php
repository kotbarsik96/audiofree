<?php

namespace App\Services\MessagesToUser;

use App\DTO\MessagesToUser\MessagesToUserDTOCollection;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Mail;

class MTUController
{
  public array $possibleChannels = [];

  public function __construct(public User $user)
  {
    $this->definePossibleChannels();
  }

  public function definePossibleChannels()
  {
    if ($this->user->email)
      $this->possibleChannels[] = 'Email';

    if ($this->user->telegram)
      $this->possibleChannels[] = 'Telegram';
  }

  public function isPossible(string $key): bool
  {
    return in_array($key, $this->possibleChannels);
  }

  /**
   * Определяет возможные каналы связи с пользователем и отправляет туда сообщение
   * @param $ables - экземпляры Mailable/Telegramable
   * @return array<string> - каналы связи, куда было отправлено собщение (например: ['Telegram', 'Email'])
   */
  public function send(...$ables): array
  {
    $sentToArr = [];

    foreach ($ables as $able) {
      if ($able instanceof Mailable && $this->isPossible('Email')) {
        Mail::to($this->user)->send($able);
        $sentToArr[] = 'Email';
      }
      if ($able instanceof Telegramable && $this->isPossible('Telegram')) {
        $sentToArr[] = 'Telegram';
      }
    }

    return $sentToArr;
  }
}
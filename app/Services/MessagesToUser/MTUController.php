<?php

namespace App\Services\MessagesToUser;

use App\Enums\MessagesToUserEnum;
use App\Models\User;
use App\Services\MessagesToUser\Telegramable\Telegramable;
use Illuminate\Mail\Mailable;
use Mail;

class MTUController
{
  /**
   * @var array<MessagesToUserEnum>
   */
  public array $possibleChannels = [];

  public function __construct(public User $user)
  {
    $this->definePossibleChannels();
  }

  public function definePossibleChannels()
  {
    if ($this->user->email)
      $this->possibleChannels[] = MessagesToUserEnum::EMAIL;

    if ($this->user->telegram)
      $this->possibleChannels[] = MessagesToUserEnum::TELEGRAM;
  }

  public function isPossible(MessagesToUserEnum $key): bool
  {
    return in_array($key, $this->possibleChannels);
  }

  /**
   * Определяет возможные каналы связи с пользователем и отправляет туда сообщение
   * @param $ables - экземпляры Mailable/Telegramable
   * @return array<MessagesToUserEnum> - каналы связи, куда было отправлено собщение (например: ['Telegram', 'Email'])
   */
  public function send(...$ables): array
  {
    $sentToArr = [];

    foreach ($ables as $able) {
      if ($able instanceof Mailable && $this->isPossible(MessagesToUserEnum::EMAIL)) {
        Mail::to($this->user)->send($able);
        $sentToArr[] = MessagesToUserEnum::EMAIL;
      }
      if ($able instanceof Telegramable && $this->isPossible(MessagesToUserEnum::TELEGRAM)) {
        $able->send($this->user);
        $sentToArr[] = MessagesToUserEnum::TELEGRAM;
      }
    }

    return $sentToArr;
  }
}
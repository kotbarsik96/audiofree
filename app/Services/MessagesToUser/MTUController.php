<?php

namespace App\Services\MessagesToUser;

use App\DTO\Enums\ConfirmationPurposeEnum;
use App\DTO\Enums\MessagesToUserEnum;
use App\Models\Confirmation;
use App\Models\User;
use App\Services\MessagesToUser\Mailable\MailableCustom;
use App\Services\MessagesToUser\Telegramable\Telegramable;
use Mail;

class MTUController
{
  /**
   * @var array<MessagesToUserEnum>
   */
  public array $possibleChannels = [];

  /**
   * Список каналов, в которые будет отправлено сообщение
   * @var array
   */
  public array $willBeSentTo = [];

  /**
   * Начать подготовку отправки сообщения
   * @param \App\Models\User $user - пользователь, которому будет отправлено сообщение
   * @param array $ables - список экземпляров классов, производных от Telegramable/Mailable
   */
  public function __construct(public User $user, public array $ables = [])
  {
    $this->definePossibleChannels();

    foreach ($ables as $able) {
      if ($this->canSendMail($able)) {
        $this->willBeSentTo[] = (string) MessagesToUserEnum::EMAIL->value;
      }
      if ($this->canSendTelegram($able)) {
        $this->willBeSentTo[] = (string) MessagesToUserEnum::TELEGRAM->value;
      }
    }
  }

  protected function canSendMail($able)
  {
    return $able instanceof MailableCustom && $this->isPossible(MessagesToUserEnum::EMAIL);
  }

  protected function canSendTelegram($able)
  {
    return $able instanceof Telegramable && $this->isPossible(MessagesToUserEnum::TELEGRAM);
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
  public function send(): array
  {
    $sentToArr = [];

    foreach ($this->ables as $able) {
      if ($this->canSendMail($able)) {
        Mail::to($this->user)->send($able);
      }
      if ($this->canSendTelegram($able)) {
        $able->send();
      }
    }

    return $sentToArr;
  }

  public static function createAndSendConfirmationCode(
    ConfirmationPurposeEnum $purpose,
    User $user,
    array $ables
  ) {
    $mtu = new static($user, $ables);

    /** Код создаётся здесь и записывается в $ables */
    Confirmation::createCode(
      $purpose,
      $user,
      $mtu
    );

    $mtu->send();

    return $mtu;
  }
}
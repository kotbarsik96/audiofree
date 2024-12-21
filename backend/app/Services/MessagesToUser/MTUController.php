<?php

namespace App\Services\MessagesToUser;

use App\Models\User;
use Illuminate\Mail\Mailable;

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
    return ['email']; // в дальнейшем делать проверку на наличие поля 'email' или 'telegram' или обоих сразу
  }

  public function isDesired(string $key): bool
  {
    return in_array($key, $this->channels);
  }

  /**
   * На основе предпочтений пользователя, отправляет сообщение в удобный канал связи/несколько каналов связи
   * @param string $ableName - первая часть названия Mailable/Telegramable
   * @param array $args - параметры, передаваемые в Mailable/Telegramable
   */
  public function send(string $ableName, ...$args)
  {
    if ($this->isDesired('email')) {

    }
    if ($this->isDesired('telegram')) {

    }
  }

  /**
   * Для того, чтобы отослать именно Mailable
   * @param string $ableName - первая часть названия Mailable
   */
  public function sendMailable(string $ableName)
  {
    
  }

  /**
   * Для того, чтобы отослать именно Telegramable
   * @param string $ableName - первая часть названия Telegramable
   */
  public function sendTelegramable(string $ableName)
  {
    
  }
}
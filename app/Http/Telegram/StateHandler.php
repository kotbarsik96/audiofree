<?php

namespace App\Http\Telegram;

use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
use App\Models\Confirmation;
use App\Models\Telegram\TelegraphChat;
use App\Services\MessagesToUser\Mailable\ConnectToTelegramMailable;
use App\Services\MessagesToUser\MTUController;
use DefStudio\Telegraph\DTO\Message;
use App\Models\User;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StateHandler
{
  public function __construct(
    public TelegraphChat $chat,
    public Message $message
  ) {
  }

  public function connectProfile(string $login)
  {
    $user = User::getByLogin($login);

    throw_if(
      !$user->email,
      new UnprocessableEntityHttpException(
        __('telegram.exceptions.impossibleToSendConfirmationCode')
      )
    );

    $mtu = new MTUController($user);
    $purpose = 'prp_connect_telegram';
    $dto = ConfirmationPurposeDTOCollection::getDTO($purpose);
    $codeData = Confirmation::createCode(
      $purpose,
      $user,
      $dto->codeLength
    );
    $codeData->update([
      'sent_to' => $mtu->send(new ConnectToTelegramMailable(
        $user,
        $codeData->unhashedCode,
        $this->message->from()->username()
      ))
    ]);

    $this->chat->setState('connectProfileEnterCode');
    $this->chat->message(
      __(
        'telegram.connectProfile.codeSent',
        ['sent_to' => implode(', ', $codeData->sent_to)]
      )
    )
      ->send();
  }

  public function connectProfileEnterCode(string $code)
  {
    $user = $this->chat->user;
    $isCodeValid = Confirmation::validateCode(
      'prp_connect_telegram',
      $user,
      $code
    );

    throw_if(
      !$isCodeValid,
      new UnprocessableEntityHttpException(
        __('telegram.exceptions.incorrectConfirmationCode')
      )
    );

    $telegramLogin = $this->message->from()->username();
    $user->update([
      'telegram' => $telegramLogin
    ]);

    $this->chat->removeState();
    $this->chat
      ->message(__(
        'telegram.connectProfile.profileConnected',
        ['login' => $telegramLogin]
      ))
      ->send();
  }

  public function onMessageOrStartCommand()
  {
    $user = User::where('telegram', $this->message->from()->username())->first();

    $chatId = $this->chat->id;
    TelegraphChat::firstOrCreate(['chat_id' => $chatId]);

    $this->chat->message(__('telegram.welcome.general'))
      ->keyboard(Keyboard::make()
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
            ->param('firstname', $this->message->from()->firstName())
            ->param('username', $this->message->from()->username())
        ))
      ->send();
  }
}
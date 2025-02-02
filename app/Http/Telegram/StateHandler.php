<?php

namespace App\Http\Telegram;

use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
use App\Models\Confirmation;
use App\Models\Telegram\TelegraphBot;
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

    $this->chat->setData(['user_id' => $user->id]);
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
    $userId = $this->chat->getDataItem('user_id');

    $isCodeValid = Confirmation::validateCode(
      'prp_connect_telegram',
      $userId,
      $code
    );

    throw_if(
      !$isCodeValid,
      new UnprocessableEntityHttpException(
        __('telegram.exceptions.incorrectConfirmationCode')
      )
    );

    $telegramLogin = $this->message->from()->username();
    $user = User::findOrFail($userId);
    $user->update([
      'telegram' => $telegramLogin
    ]);

    $this->chat->removeState();
    $this->chat->removeData();
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

    TelegraphChat::firstOrCreate([
      'chat_id' => $this->chat->chat_id,
      'telegraph_bot_id' => TelegraphBot::first()->id
    ]);

    $this->chat->message(__('telegram.welcome.general'))
      ->keyboard(
        TelegraphKeyboard::onMessage($this->message, $user)
      )
      ->send();
  }
}
<?php

namespace App\Http\Telegram;

use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
use App\Models\Confirmation;
use \DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;
use App\Models\User;
use DefStudio\Telegraph\DTO\User as TelegraphUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class Handler extends WebhookHandler
{
  protected function onFailure(\Throwable $throwable): void
  {
    report($throwable);

    if (
      $throwable instanceof NotFoundHttpException
      || $throwable instanceof UnprocessableEntityHttpException
      || env('DEV_MODE', false)
    ) {
      $this->chat->message($throwable->getMessage())->send();
    } else {
      $this->chat->message(__('telegram.exceptions.failure'))->send();
    }
  }

  protected function handleChatMessage(Stringable $text): void
  {
    HandlerActions::onMessageOrStartCommand($this->chat, $this->message);
  }

  public function start()
  {
    HandlerActions::onMessageOrStartCommand($this->chat, $this->message);
  }

  public function register()
  {
    $firstname = $this->data->get('firstname') ?? $this->message->from()->firstName();
    $username = $this->data->get('username') ?? $this->message->from()->username();

    throw_if(
      !$firstname || !$username,
      new UnprocessableEntityHttpException(__('telegram.exceptions.unknownCommand'))
    );

    $user = User::where('telegram', $username)->first();
    throw_if(
      $user,
      new UnprocessableEntityHttpException(__('telegram.exceptions.alreadyRegistered'))
    );

    $user = User::create([
      'name' => $firstname,
      'telegram' => $username,
      'telegram_chat_id' => $this->chat->chat_id
    ]);

    $siteUrl = env('APP_FRONTEND_LINK');
    $purpose = 'prp_login';
    $codeData = Confirmation::createCode(
      $purpose,
      $user,
      ConfirmationPurposeDTOCollection::getDTO($purpose)->codeLength
    );
    $codeData->update(['sent_to' => ['Telegram']]);
    $this->chat->message(__('telegram.welcome.user', [
      'name' => $user->name
    ]))
      ->keyboard(Keyboard::make()
        ->buttons([
          Button::make(__('telegram.button.goToSite'))
            ->url("$siteUrl?auth_login=$user->telegram&auth_code=$codeData->unhashedCode")
        ]))
      ->send();
  }
}
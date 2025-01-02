<?php

namespace App\Http\Telegram;

use \DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;
use App\Models\User;
use DefStudio\Telegraph\DTO\User as TelegraphUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Handler extends WebhookHandler
{
  protected function onFailure(\Throwable $throwable): void
  {
    report($throwable);

    if (
      $throwable instanceof NotFoundHttpException
      || $throwable instanceof UnprocessableEntityHttpException
    ) {
      $this->chat->message($throwable->getMessage())->send();
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
    $firstname = $this->data->get('firstname');
    $username = $this->data->get('username');

    throw_if(
      !$firstname || !$username,
      new UnprocessableEntityHttpException(__('telegram.exceptions.unknownCommand'))
    );

    $user = User::where('telegram', $username)->first();
    throw_if(
      $user,
      new UnprocessableEntityHttpException(__('abortions.userExists'))
    );

    $user = User::create([
      'name' => $firstname,
      'telegram' => $username,
      'telegram_chat_id' => $this->chat->id
    ]);

    $siteUrl = env('APP_FRONTEND_LINK');
    $token = $user->createToken(time())->plainTextToken;
    $this->chat->html(__('telegram.welcome.user', [
      'name' => $firstname,
      'link' => "<a href=\"$siteUrl?token=$token\">ссылке</a>",
      'site' => "<a href=\"$siteUrl\">$siteUrl</a>"
    ]))->send();
  }
}
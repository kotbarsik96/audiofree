<?php

namespace App\Http\Controllers;

use App\DTO\Auth\AuthDTOCollection;
use App\Services\MessagesToUser\Telegramable\LoginTelegramable;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\User;

class TestController extends Controller
{
  public function test(Request $request)
  {
    Telegraph::message('test')->send();
    // (new LoginTelegramable(432))->send(User::find(1));
  }
}

<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
  public function test(Request $request)
  {
    Telegraph::chat('')->message('test')->send();
  }
}

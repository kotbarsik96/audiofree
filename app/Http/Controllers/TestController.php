<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
  public function test(Request $request)
  {
    return User::where('telegram', 'kotbarsik96')->first()->telegramChat;
  }
}

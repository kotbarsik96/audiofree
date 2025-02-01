<?php

namespace App\Models\Telegram;

use App\Models\Telegram\TelegraphChat;
use DefStudio\Telegraph\Models\TelegraphBot as BaseModel;

class TelegraphBot extends BaseModel
{
  public static function createChat(array $attributes = [])
  {
    return self::first()->chats()->create($attributes);
  }

  public static function getUserChat(int $userId)
  {
    return self::first()->chats()->where('user_id', $userId)->first();
  }
}

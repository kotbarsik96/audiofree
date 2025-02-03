<?php

namespace App\Models\Telegram;

use App\Models\Telegram\TelegraphChat;
use DefStudio\Telegraph\Models\TelegraphBot as BaseModel;

class TelegraphBot extends BaseModel
{

  /**
   * Создаст чат, если его ещё нет, либо вернёт существующий чат. 
   * 
   * Если у чата не было user_id, но в $attributes он есть, то выставит его
   */
  public static function createChat(array $attributes = [])
  {
    $userId = null;
    // сохранить $userId и убрать его из attributes, т.к. чат может быть без пользователя, но id чата не меняется, т.е. таким образом НЕ создаётся дубликат чата в бд
    if (array_key_exists('user_id', $attributes)) {
      $userId = $attributes['user_id'];
      unset($attributes['user_id']);
    }

    // если в атрибутах есть id пользователя, обновить запись. В другом случае, не делать этого
    $chat = self::first()->chats()->firstOrCreate($attributes);
    if ($userId && $chat->user_id !== $userId) {
      $chat->update(['user_id' => $userId]);
    }

    return $chat;
  }

  public static function getUserChat(int $userId)
  {
    return self::first()->chats()->where('user_id', $userId)->first();
  }
}

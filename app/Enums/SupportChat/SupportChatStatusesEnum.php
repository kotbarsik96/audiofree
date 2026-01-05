<?php

namespace App\Enums\SupportChat;

enum SupportChatStatusesEnum: string
{
  case OPEN = 'open';
  case CLOSED = 'closed';

  public static function values()
  {
    return array_column(self::cases(), 'value');
  }

  public static function fromValue(string $value)
  {
    return match ($value) {
      'open' => static::OPEN,

      'closed' => static::CLOSED
    };
  }
}
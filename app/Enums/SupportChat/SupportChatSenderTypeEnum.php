<?php

namespace App\Enums\SupportChat;

enum SupportChatSenderTypeEnum: string
{
  case USER = 'user';

  case STAFF = 'staff';

  public static function values()
  {
    return array_column(self::cases(), 'value');
  }
}
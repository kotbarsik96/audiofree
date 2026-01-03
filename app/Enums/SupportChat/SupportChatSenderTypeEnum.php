<?php

namespace App\Enums\SupportChat;

enum SupportChatSenderTypeEnum: string
{
  case USER = 'user';

  case STAFF = 'staff';

  case SYSTEM = 'system';

  public static function values()
  {
    return array_column(self::cases(), 'value');
  }
}
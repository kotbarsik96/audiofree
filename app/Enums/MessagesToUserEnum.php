<?php

namespace App\Enums;

enum MessagesToUserEnum: string {
  case TELEGRAM = 'Telegram';
  case EMAIL = 'Email';
}
<?php

namespace App\Enums;

enum ConfirmationPurposeEnum: string {
  case VERIFY_EMAIL = 'prp_verify_email';
  case RESET_PASSWORD = 'prp_reset_password';
  case LOGIN = 'prp_login';
  case CONNECT_TELEGRAM = 'prp_connect_telegram';
}
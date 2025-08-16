<?php

namespace App\DTO\Enums;

use App\DTO\ConfirmationPurposeDTO;

enum ConfirmationPurposeEnum: string
{
  case VERIFY_EMAIL = 'prp_verify_email';
  case RESET_PASSWORD = 'prp_reset_password';
  case LOGIN = 'prp_login';
  case CONNECT_TELEGRAM = 'prp_connect_telegram';

  public function dto()
  {
    return match ($this) {
      ConfirmationPurposeEnum::VERIFY_EMAIL => new ConfirmationPurposeDTO(600, 6),
      ConfirmationPurposeEnum::RESET_PASSWORD => new ConfirmationPurposeDTO(600, 6),
      ConfirmationPurposeEnum::LOGIN => new ConfirmationPurposeDTO(300, 6),
      ConfirmationPurposeEnum::CONNECT_TELEGRAM => new ConfirmationPurposeDTO(300, 6)
    };
  }
}
<?php

namespace App\DTO\ConfirmationPurpose;

use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTO;
use App\DTO\DTOCollection;
use App\Enums\ConfirmationPurposeEnum;

/**
 * @extends DTOCollection<ConfirmationPurposeDTO>
 */
class ConfirmationPurposeDTOCollection extends DTOCollection
{
  public static $enum = ConfirmationPurposeEnum;

  public static function getDTO($key): ConfirmationPurposeDTO
  {
    return match ($key) {
      ConfirmationPurposeEnum::VERIFY_EMAIL => new ConfirmationPurposeDTO(600, 6),
      ConfirmationPurposeEnum::RESET_PASSWORD => new ConfirmationPurposeDTO(600, 6),
      ConfirmationPurposeEnum::LOGIN => new ConfirmationPurposeDTO(300, 6),
      ConfirmationPurposeEnum::CONNECT_TELEGRAM => new ConfirmationPurposeDTO(300, 6)
    };
  }
}

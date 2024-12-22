<?php

namespace App\DTO\ConfirmationPurpose;

use App\DTO\Confirmation\ConfirmationPurposeDTO;
use App\DTO\DTOCollection;

ConfirmationPurposeDTOCollection::register(
  'verify_email',
  new ConfirmationPurposeDTO(6, 600)
);

ConfirmationPurposeDTOCollection::register(
  'reset_password',
  new ConfirmationPurposeDTO(6, 600)
);

/**
 * @extends DTOCollection<ConfirmationPurposeDTO>
 */
class ConfirmationPurposeDTOCollection extends DTOCollection
{
}
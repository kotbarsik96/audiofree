<?php

namespace App\DTO\ConfirmationPurpose;

use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTO;
use App\DTO\DTOCollection;

/**
 * @extends DTOCollection<ConfirmationPurposeDTO>
 */
class ConfirmationPurposeDTOCollection extends DTOCollection
{
}

ConfirmationPurposeDTOCollection::register(
  'prp_verify_email',
  new ConfirmationPurposeDTO(6, 600)
);

ConfirmationPurposeDTOCollection::register(
  'prp_reset_password',
  new ConfirmationPurposeDTO(6, 600)
);
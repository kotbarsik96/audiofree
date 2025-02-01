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
  new ConfirmationPurposeDTO(600, 6)
);

ConfirmationPurposeDTOCollection::register(
  'prp_reset_password',
  new ConfirmationPurposeDTO(600, 6)
);

ConfirmationPurposeDTOCollection::register(
  'prp_login',
  new ConfirmationPurposeDTO(300, 6)
);

ConfirmationPurposeDTOCollection::register(
  'prp_connect_telegram',
  new ConfirmationPurposeDTO(300, 6)
);

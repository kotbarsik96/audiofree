<?php

namespace App\DTO;

use App\DTO\Enums\Catalogs\SortCatalog;

class SortDTO
{
  /** @param array<SortCatalog> $sorts */
  public function __construct(
    public array $sorts
  ) {
  }
}

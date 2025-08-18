<?php

namespace App\DTO;

use App\DTO\Catalogs\SortCatalog;

class SortDTO
{
  /** @param array<SortCatalog> $sorts */
  public function __construct(
    public array $sorts
  ) {
  }
}

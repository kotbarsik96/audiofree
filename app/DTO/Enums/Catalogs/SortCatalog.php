<?php

namespace App\DTO\Enums\Catalogs;

class SortCatalog
{
  public function __construct(
    public string $label,
    public string $value
  ) {
  }
}
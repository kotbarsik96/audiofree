<?php

namespace App\DTO\Catalogs;

class SortCatalog
{
  public function __construct(
    public string $label,
    public string $value
  ) {
  }
}
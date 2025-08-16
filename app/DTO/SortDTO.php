<?php

namespace App\DTO;

class SortDTO
{
  /** @param array<SortItem> $sorts */
  public function __construct(
    public array $sorts
  ) {
  }
}

class SortItem
{
  public function __construct(
    public string $label,
    public string $value
  ) {
  }
}
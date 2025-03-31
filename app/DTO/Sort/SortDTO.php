<?php

namespace App\DTO\Sort;

class SortDTO
{
  /** @param array<array{label: string, value: string}> $sorts */
  public function __construct(
    public array $sorts
  ) {
  }
}
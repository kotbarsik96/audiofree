<?php

namespace App\DTO\Confirmation;

class ConfirmationPurposeDTO
{
  private function __construct(
    public int $ttl,
    public int $codeLength,
  ) {
  }
}
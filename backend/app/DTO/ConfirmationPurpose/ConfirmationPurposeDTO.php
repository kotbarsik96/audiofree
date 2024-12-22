<?php

namespace App\DTO\ConfirmationPurpose;

class ConfirmationPurposeDTO
{
  public function __construct(
    public int $ttl,
    public int $codeLength,
  ) {
  }
}
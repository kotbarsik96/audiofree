<?php

namespace App\DTO;

class ConfirmationPurposeDTO
{
  public function __construct(
    public int $ttl,
    public int $codeLength,
  ) {
  }
}
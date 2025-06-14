<?php

namespace App\Services;

class StringsService
{
  public static function resetLink(string $code, string $login): string
  {
    return env("APP_FRONTEND_LINK", "")
      . "/confirmation/reset-password?code=" . $code . "&login=" . $login;
  }

  public static function enumToStringsArray($cases)
  {
    return array_map(
      fn($case) => $case->value,
      $cases
    );
  }
}
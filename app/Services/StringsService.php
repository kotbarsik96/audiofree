<?php

namespace App\Services;

class StringsService
{
  public static function resetLink(string $code, string $login): string
  {
    return env("APP_FRONTEND_LINK", "")
      . "/confirmation/reset-password?code=" . $code . "&login=" . $login;
  }
}
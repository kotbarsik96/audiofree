<?php

namespace App\DTO\Enums\Traits;

trait EnumHelper
{
  public static function dtoCases(...$args)
  {
    return array_map(fn($e) => $e->dto(...$args), static::cases());
  }

  public static function caseExists(string $case)
  {
    $casesValues = array_map(fn($e) => $e->value, static::cases());

    return in_array(
      $case,
      $casesValues
    );
  }
}
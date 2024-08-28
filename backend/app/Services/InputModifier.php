<?php

namespace App\Services;

class InputModifier
{
  public static function stringToNumber(string | null $value)
  {
    return $value ? (int) preg_replace('/[^\.0-9]/', '', $value) : null;
  }
}

<?php

namespace App\DTO;

use App\DTO\Auth\AuthDTO;

/**
 * @template T
 */
class DTOCollection
{
  /**
   * @return T
   */
  public static function getDTO($key)
  {
    return match ($key) {};
  }

  /**
   * @return array<string, T>
   */
  public static function getAllDTOs($enum): array
  {
    $arr = [];
    foreach ($enum::cases() as $key) {
      array_push($arr, self::getDTO($key));
    }
    return $arr;
  }
}
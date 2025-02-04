<?php

namespace App\DTO;

use App\Enums\AuthEnum;

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
  
  public static function getAllDTOs($keys)
  {
    return array_map(fn($key) => static::getDTO($key), $keys);
  }
}
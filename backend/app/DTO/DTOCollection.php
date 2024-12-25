<?php

namespace App\DTO;

/**
 * @template T
 */
class DTOCollection
{
  private static self $instance;

  /**
   * @var array<string, T>
   */
  private $instances = [];

  private static function getInstance(): self
  {
    if (!isset(self::$instance))
      self::$instance = new static();
    return self::$instance;
  }

  public static function register(string $key, $dtoInstance)
  {
    self::getInstance()->instances[$key] = $dtoInstance;
  }

  /**
   * @return T
   */
  public static function getDTO(string $dtoName)
  {
    return self::getInstance()->instances[$dtoName];
  }
}
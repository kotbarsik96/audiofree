<?php

namespace App\DTO\Auth;

use App\DTO\Auth\AuthDTO;
use App\DTO\DTOCollection;

/**
 * @extends DTOCollection<AuthDTO>
 */
class AuthDTOCollection extends DTOCollection
{
  /**
   * Возвращает возможные массив возможных вариантов авторизации
   * @return array<int, string>
   */
  public static function getPossibleAuths()
  {
    $dtos = self::getAllDTOs();
    $arr = [];
    foreach ($dtos as $dto) {
      $arr[] = $dto->name;
    }
    return $arr;
  }

  /**
   * Делает то же, что self::getPossibleAuths, но убирает $except
   * @param $except строка/массив строк, которые нужно исключить из массива
   * @return array<int, string>
   */
  public static function getPossibleAuthsWithout(string|array $except)
  {
    if (gettype($except) === 'string')
      $except = [$except];

    return array_filter(
      self::getPossibleAuths(),
      fn(string $str) => !in_array($str, $except)
    ); // проверить
  }
}

AuthDTOCollection::register('email', new AuthDTO('email'));
AuthDTOCollection::register('telegram', new AuthDTO('telegram'));
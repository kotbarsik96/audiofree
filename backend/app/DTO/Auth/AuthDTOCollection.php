<?php

namespace App\DTO\Auth;

use App\DTO\Auth\AuthDTO;
use App\DTO\DTOCollection;
use App\Models\User;
use App\Services\MessagesToUser\Mailable\LoginMailable;
use App\Services\MessagesToUser\Mailable\VerifyEmailMailable;

/**
 * @extends DTOCollection<AuthDTO>
 */
class AuthDTOCollection extends DTOCollection
{
  /**
   * Возвращает массив возможных вариантов авторизации
   * @return array<int, string>
   */
  public static function getPossibleAuths()
  {
    $dtos = self::getAllDTOs();
    $arr = [];
    foreach ($dtos as $dto) {
      $arr[] = $dto->columnName;
    }
    return $arr;
  }

  /**
   * Делает то же, что self::getPossibleAuths, но убирает $except
   * @param $except строка/массив строк, которые нужно исключить из массива
   * @return array<int, string> | string
   */
  public static function getPossibleAuthsWithout(string|array $except, $returnAsString = false): array|string
  {
    if (gettype($except) === 'string')
      $except = [$except];

    $auths = array_filter(
      self::getPossibleAuths(),
      fn(string $str) => !in_array($str, $except)
    );

    if ($returnAsString)
      $auths = implode(',', $auths);

    return $auths;
  }

  /**
   * Получить DTO по логину пользователя
   */
  public static function getDTOByLogin(User $user, string $login): AuthDTO|null
  {
    $searchedDto = null;
    foreach (self::getAllDTOs() as $dto) {
      $columnName = $dto->columnName;
      if ($user->$columnName === $login) {
        $searchedDto = $dto;
        break;
      }
    }
    return $searchedDto;
  }
}

/** При добавлении новых AuthDTO, помнить, что нужно указывать ключи в:
 * в виде колонки в таблице users (<key>, <key>_verified_at)
 * в виде строки массива User::fillable
 * в AuthValidation
 * в SignupRequest
 */

AuthDTOCollection::register(
  'email',
  new AuthDTO(
    'email',
    LoginMailable::class,
    'email_verified_at',
    VerifyEmailMailable::class
  )
);

// AuthDTOCollection::register(
//   'telegram',
//   new AuthDTO(
//     'telegram',
//     LoginMailable::class
//   )
// );

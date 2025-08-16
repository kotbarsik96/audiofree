<?php

namespace App\DTO\Enums;

use App\DTO\AuthDTO;
use App\Models\User;
use App\Services\MessagesToUser\Mailable\LoginMailable;
use App\Services\MessagesToUser\Mailable\VerifyEmailMailable;
use App\Services\MessagesToUser\Telegramable\LoginTelegramable;

enum AuthEnum: string
{
  case TELEGRAM = 'telegram';
  case EMAIL = 'email';

  /** При добавлении новых AuthDTO, помнить, что нужно указывать ключи в:
   * в виде колонки в таблице users (<key>, <key>_verified_at)
   * в виде строки массива User::fillable
   * в AuthValidation
   * в SignupRequest
   */
  public function dto()
  {
    return match ($this) {
      AuthEnum::EMAIL => new AuthDTO(
        'email',
        LoginMailable::class,
        'email_verified_at',
        VerifyEmailMailable::class
      ),

      AuthEnum::TELEGRAM => new AuthDTO(
        'telegram',
        LoginTelegramable::class,
        false,
        false,
      )
    };
  }

  /**
   * Возвращает массив возможных вариантов авторизации, если не указан $separator
   * 
   * Если указан $separator, возвращает строку вариантов, разделённых по $separator
   * @return array<int, string>|string
   */
  public static function getPossibleAuths(string|null $separator = null)
  {
    $dtos = AuthEnum::cases();
    $arr = [];
    foreach ($dtos as $dto) {
      $arr[] = $dto->dto()->columnName;
    }
    if ($separator)
      $arr = implode($separator, $arr);
    return $arr;
  }

  /**
   * Делает то же, что static::getPossibleAuths, но убирает $except
   * @param $except строка/массив строк, которые нужно исключить из массива
   * @return array<int, string> | string
   */
  public static function getPossibleAuthsWithout(string|array $except, $returnAsString = false): array|string
  {
    if (gettype($except) === 'string')
      $except = [$except];

    $auths = array_filter(
      static::getPossibleAuths(),
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
    foreach (self::cases() as $dtoEnum) {
      $columnName = $dtoEnum->dto()->columnName;
      if ($user->$columnName === $login) {
        $searchedDto = $dtoEnum->dto();
        break;
      }
    }
    return $searchedDto;
  }

  public static function fromValue(string $value): AuthEnum
  {
    return match ($value) {
      'email' => AuthEnum::EMAIL,
      'telegram' => AuthEnum::TELEGRAM
    };
  }

  public static function authToPurpose(AuthEnum $enum): ConfirmationPurposeEnum
  {
    return match ($enum) {
      AuthEnum::EMAIL => ConfirmationPurposeEnum::VERIFY_EMAIL
    };
  }
}
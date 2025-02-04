<?php

namespace App\DTO\Auth;

use App\DTO\Auth\AuthDTO;
use App\DTO\DTOCollection;
use App\Enums\AuthEnum;
use App\Enums\ConfirmationPurposeEnum;
use App\Interfaces\IDTOCollection;
use App\Models\User;
use App\Services\MessagesToUser\Mailable\LoginMailable;
use App\Services\MessagesToUser\Mailable\VerifyEmailMailable;
use App\Services\MessagesToUser\Telegramable\LoginTelegramable;

/**
 * @extends DTOCollection<AuthDTO>
 */
class AuthDTOCollection extends DTOCollection implements IDTOCollection
{ 
  /** При добавлении новых AuthDTO, помнить, что нужно указывать ключи в:
   * в виде колонки в таблице users (<key>, <key>_verified_at)
   * в виде строки массива User::fillable
   * в AuthValidation
   * в SignupRequest
   */
  public static function getDTO($key): AuthDTO
  {
    return match ($key) {
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
    $dtos = AuthDTOCollection::getAllDTOs(AuthEnum::cases());
    $arr = [];
    foreach ($dtos as $dto) {
      $arr[] = $dto->columnName;
    }
    if ($separator)
      $arr = implode($separator, $arr);
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
    foreach (self::getAllDTOs(AuthEnum::cases()) as $dto) {
      $columnName = $dto->columnName;
      if ($user->$columnName === $login) {
        $searchedDto = $dto;
        break;
      }
    }
    return $searchedDto;
  }

  public static function entityToVerificationEnum(string $entity): AuthEnum
  {
    return match ($entity) {
      'email' => AuthEnum::EMAIL,
    };
  }

  public static function entityToPurpose(AuthEnum $entity): ConfirmationPurposeEnum
  {
    return match($entity) {
      AuthEnum::EMAIL => ConfirmationPurposeEnum::VERIFY_EMAIL
    };
  }
}

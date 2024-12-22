<?php

namespace App\Models;

use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Services\CodePhrase;

class Confirmation extends BaseModel
{
  use HasFactory;

  protected $table = 'confirmations';

  protected $fillable = [
    'user_id',
    'code',
    'sent_to',
    'purpose',
    'expires',
  ];

  public static function getTtl($purpose)
  {
    return ConfirmationPurposeDTOCollection::getDTO($purpose)->ttl;
  }

  public static function getCodeLength($purpose)
  {
    return ConfirmationPurposeDTOCollection::getDTO($purpose)->codeLength;
  }

  /**
   * Получить дату истечения действия кода (к текущему timestamp'у прибавить указанное кол-во секунд)
   * @param $timestamp - кол-во секунд
   */
  public static function getExpirationTime($timeout)
  {
    return Carbon::createFromTimestamp(
      Carbon::now()->timestamp + $timeout
    );
  }

  /**
   * Создаёт код по указанной цели пользователю
   * @param string $purpose - цель. Должна быть зарегистрирована в App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
   * @param \App\Models\User $user - пользователь
   */
  public static function createCode(string $purpose, User $user): static
  {
    $code = CodePhrase::generateNumeric(self::getCodeLength($purpose));
    $hashedCode = Hash::make($code);
    $ttl = self::getTtl($purpose);

    $codeData = self::create([
      'user_id' => $user->id,
      'code' => $hashedCode,
      'purpose' => $purpose,
      'expires' => self::getExpirationTime($ttl),
      'sent_to' => '[]'
    ]);

    return $codeData;
  }

  /**
   * Удалить код, высланный пользователю для конкретной цели
   */
  public static function deleteForPurpose($user, $purpose)
  {
    self::purposeUser($purpose, $user->id)
      ->delete();
  }

  /**
   * Проверяет, выслан ли уже пользователю код для этой цели
   * @return string|false - строка, что код выслан, либо false
   */
  public static function checkIfValidCodeExists(
    $purpose,
    $userId
  ): string|false {
    $data = self::purposeUser($purpose, $userId)->first();
    if ($data) {
      return __(
        'general.codeAlreadySentTo',
        ['sentTo' => implode(', ', $data->sent_to)]
      );

    }
    return false;
  }

  /**
   * Проверит код пользователя, сравнив его с захэшированной версией в бд
   */
  public static function validateCode(string $purpose, int $userId, string $code)
  {
    $hashedCode = self::where('purpose', $purpose)
      ->where('user_id', $userId)
      ->first() ?? '1';

    return Hash::check($code, $hashedCode);
  }

  public function scopePurposeUser(Builder $query, $purpose, $userId)
  {
    $query->where('purpose', $purpose)->where('user_id', $userId);
  }
}

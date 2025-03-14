<?php

namespace App\Models;

use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
use App\Enums\ConfirmationPurposeEnum;
use App\Services\MessagesToUser\MTUController;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Services\CodePhrase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Confirmation extends BaseModel
{
  use HasFactory;

  public string $unhashedCode;

  protected $table = 'confirmations';

  protected $fillable = [
    'user_id',
    'code',
    'sent_to',
    'purpose',
    'expires',
  ];

  protected $casts = [
    'sent_to' => 'json'
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
   * Создаёт код по указанной цели пользователю. 
   * 
   * Если передан $mtu - отправит код автоматически. Но, в этом случае, если некуда отправить код - выбросит ошибку
   * @param string $purpose - цель. Должна быть зарегистрирована в App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
   * @param \App\Models\User $user - пользователь
   */
  public static function createCode(
    ConfirmationPurposeEnum $purpose,
    User $user,
    ?MTUController $mtu = null
  ): static {
    $code = CodePhrase::generateNumeric(self::getCodeLength($purpose));
    $hashedCode = Hash::make($code);
    $ttl = self::getTtl($purpose);

    /** Если передан экземпляр MessagesToUserController'а, осуществить проверки и записать код */
    if (!!$mtu) {
      /** Если код не может быть никуда отправлен - выбросить ошибку */
      throw_if(
        count($mtu->willBeSentTo) < 1,
        new UnprocessableEntityHttpException(__('abortions.messageNotSend'))
      );

      foreach ($mtu->ables as $able) {
        $able->setCode($code);
      }
    }

    $codeData = self::create([
      'user_id' => $user->id,
      'code' => $hashedCode,
      'purpose' => $purpose,
      'expires' => self::getExpirationTime($ttl),
      'sent_to' => $mtu?->willBeSentTo ?? '[]'
    ]);
    $codeData->unhashedCode = $code;

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
   * @param $throwError - выбросит ошибку (abort(403)), если действительный код найден
   * @return string|false - строка с сообщением, что код выслан, либо false
   */
  public static function checkIfValidCodeExists(
    $purpose,
    $userId,
    $throwError = false
  ): string|false {
    $msgOrFalse = false;

    $data = self::purposeUser($purpose, $userId)->first();
    if ($data) {
      $sentTo = is_array($data->sent_to) ? $data->sent_to : [];
      $msgOrFalse = __(
        'general.codeAlreadySentTo',
        ['sentTo' => implode(', ', $sentTo)]
      );

      if ($throwError) {
        abort(400, $msgOrFalse);
      }
    }

    return $msgOrFalse;
  }

  /**
   * Проверит код пользователя, сравнив его с захэшированной версией в бд
   */
  public static function validateCode($purpose, int $userId, string $code)
  {
    $hashedCodeData = self::select('code')
      ->purposeUser($purpose, $userId)
      ->first();
    if (!$hashedCodeData)
      return false;

    return Hash::check((string) $code, $hashedCodeData->code);
  }

  public function scopePurposeUser(Builder $query, $purpose, $userId)
  {
    $query->where('purpose', $purpose)->where('user_id', $userId);
  }
}

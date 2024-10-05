<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Services\CodePhrase;
use Exception;
use Illuminate\Support\Facades\Mail;

class EmailConfirmation extends Model
{
  use HasFactory;

  protected $table = 'email_confirmations';

  protected $fillable = [
    'user_id',
    'code',
    'purpose',
    'expires',
  ];

  public static $purposes = [
    'verify_email' => [
      'codeLength' => 6,
      'ttl' => 600
    ],
    'reset_password' => [
      'codeLength' => null,
      'ttl' => 600
    ]
  ];

  public static function getTtl($purpose)
  {
    return self::$purposes[$purpose]['ttl'];
  }

  public static function getCodeLength($purpose)
  {
    return self::$purposes[$purpose]['codeLength'];
  }

  public function scopePurposeUser(Builder $query, $purpose, $userId)
  {
    $query->where('purpose', $purpose)->where('user_id', $userId);
  }

  public function scopeForPurpose(Builder $query, $code, $purpose)
  {
    $query->where('purpose', $purpose)
      ->where('code', $code);
  }

  public static function deleteForPurpose($user, $purpose)
  {
    self::purposeUser($purpose, $user->id)
      ->delete();
  }

  public static function getExpirationTime($plusTime)
  {
    return Carbon::createFromTimestamp(
      Carbon::now()->timestamp + $plusTime
    );
  }

  public static function generateHashedCode($purpose)
  {
    $hashedCode = null;
    do {
      $hashedCode = CodePhrase::generate(self::getCodeLength($purpose));
    } while (self::forPurpose($hashedCode, $purpose)->first());

    return $hashedCode;
  }

  public static function sendEmail($purpose, $email, $mailable)
  {
    $user = User::getByEmail($email);
    if (!$user)
      abort(404, __('abortions.userNotFound'));

    try {
      self::checkIfValidCodeExists($purpose, $user->id);
    } catch (Exception $err) {
      abort(403, $err->getMessage());
    }

    $code = self::generateHashedCode($purpose);
    $ttl = self::getTtl($purpose);

    self::create([
      'user_id' => $user->id,
      'code' => $code,
      'purpose' => $purpose,
      'expires' => self::getExpirationTime($ttl)
    ]);

    Mail::to($user)->send(new $mailable($code, $user));

    return true;
  }

  public static function verifyLink($purpose, $email, $hashedCode)
  {
    $user = User::getByEmail($email);
    if (!$user)
      abort(404, __('abortions.userNotFound'));

    $codeData = self::validateCode($purpose, $user->id, $hashedCode);
    if (!$codeData)
      abort(401, __('validation.incorrectLink'));

    return true;
  }

  public static function checkIfValidCodeExists(
    $purpose,
    $userId
  ) {
    $validCodeRow = self::purposeUser($purpose, $userId)->first();
    if ($validCodeRow) {
      throw new Exception(__('general.emailAlreadySent'));
    }

    return false;
  }

  public static function validateCode($purpose, $userId, $hashedCode)
  {
    $codeData = self::purposeUser($purpose, $userId)
      ->where('code', $hashedCode)
      ->first();

    return $codeData;
  }
}

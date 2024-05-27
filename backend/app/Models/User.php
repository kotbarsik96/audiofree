<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Exceptions\EmailConfirmationException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\Models\EmailConfirmation;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;

  protected $fillable = [
    'name',
    'surname',
    'patronymic',
    'email',
    'password',
    'phone_number',
    'location',
    'street',
    'house',
    'role',
    'email_verified_at'
  ];
  protected $hidden = [
    'password',
    'remember_token',
  ];
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public static function authUser()
  {
    $user = auth()->user();

    if (!$user)
      abort(401, __('abortions.unauthorized'));

    return $user;
  }

  public static function authUserEloquent()
  {
    return User::find(self::authUser()->id);
  }

  public static function sendEmailVerifyCode(string | null $reason = null)
  {
    $purpose = 'verify_email';

    $user = self::authUser();

    if ($user->email_verified_at) {
      abort(400, "Почта уже подтверждена");
    }

    $validCodeErr = EmailConfirmation::checkIfValidCodeExists($purpose, $user->id);
    if ($validCodeErr) return $validCodeErr;

    $code = EmailConfirmation::generateHashedCode($purpose);

    Mail::to($user)->send(new VerifyEmail($code, $reason));

    EmailConfirmation::create([
      'user_id' => $user->id,
      'code' => $code,
      'purpose' => $purpose,
      'expires' => EmailConfirmation::getExpirationTime(
        EmailConfirmation::getTtl($purpose)
      )
    ]);
  }

  public static function verifyEmail()
  {
    $purpose = 'verify_email';
    $user = self::authUser();

    try {
      EmailConfirmation::validateCode($purpose, $user->id, request('code'));
    } catch (EmailConfirmationException $e) {
      return $e->incorrectCode();
    }

    EmailConfirmation::deleteForPurpose($user, $purpose);

    $user = self::find($user->id);
    $user->update([
      'email_verified_at' => Carbon::now()
    ]);
    return $user;
  }

  public static function sendResetPasswordLink()
  {
    $purpose = 'reset_password';

    $user = self::getByEmail(request('email'));

    $validCodeErr = EmailConfirmation::checkIfValidCodeExists(
      $purpose,
      $user->id,
      'Ссылка уже была отправлена на почту'
    );
    if ($validCodeErr) return $validCodeErr;

    $code = EmailConfirmation::generateHashedCode($purpose);

    Mail::to($user)->send(new ResetPassword($code, $user));

    EmailConfirmation::create([
      'user_id' => $user->id,
      'code' => $code,
      'purpose' => $purpose,
      'expires' =>  EmailConfirmation::getExpirationTime(
        EmaiLConfirmation::getTtl($purpose)
      )
    ]);
  }

  public static function verifyResetPasswordCode($password, $code)
  {
    $purpose = 'reset_password';

    $user = self::getByEmail(request('email'));
    $codeData = EmailConfirmation::validateCode($purpose, $user->id, $code);

    $user = self::find($codeData->user_id);

    $user->update([
      'password' => Hash::make($password)
    ]);

    EmailConfirmation::deleteForPurpose($user, $purpose);
  }

  public static function changeEmail($newEmail)
  {
    $user = self::authUserEloquent();
    $user->update([
      'email_verified_at' => null,
      'email' => $newEmail
    ]);
    User::sendEmailVerifyCode('был изменен адрес электронной почты');
  }

  public static function changePassword($newPassword)
  {
    $user = self::authUserEloquent();
    $user->update([
      'password' => Hash::make($newPassword)
    ]);

    return $user;
  }

  public static function getBy($byRow, $value)
  {
    $user = self::where($byRow, $value)->first();
    if (!$user) {
      abort(401, 'Пользователь не найден');
    }

    return $user;
  }

  public static function getByEmail($email)
  {
    return self::getBy('email', $email);
  }
}

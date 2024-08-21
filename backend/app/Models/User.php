<?php

namespace App\Models;

use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;
use App\Exceptions\EmailConfirmationException;
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

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
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

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
    'permissions',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'permissions'          => 'array',
    'password'             => 'hashed',
    'email_verified_at'    => 'datetime',
  ];

  /**
   * The attributes for which you can use filters in url.
   *
   * @var array
   */
  protected $allowedFilters = [
    'id'         => Where::class,
    'name'       => Like::class,
    'email'      => Like::class,
    'updated_at' => WhereDateStartEnd::class,
    'created_at' => WhereDateStartEnd::class,
  ];

  /**
   * The attributes for which can use sort in url.
   *
   * @var array
   */
  protected $allowedSorts = [
    'id',
    'name',
    'email',
    'updated_at',
    'created_at',
  ];

  /** 
   * Проверяет, авторизован ли пользователь
   */
  public static function authUser()
  {
    $user = auth()->user();

    if (!$user)
      abort(401, __('abortions.unauthorized'));

    return $user;
  }

  /** 
   * Проверяет, авторизован ли пользователь и возвращает его объект
   */
  public static function authUserEloquent()
  {
    return User::find(self::authUser()->id);
  }

  /** 
   * Отправить код подтверждения эл.почты
   * 
   * @param $reason = заголовок подтверждения
   */
  public static function sendEmailVerifyCode(string | null $reason = null)
  {
    $purpose = 'verify_email';

    $user = self::authUser();

    if ($user->email_verified_at) {
      abort(400, __('validation.emailAlreadyVerified'));
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

  /** 
   * Подтверждение эл. почты по коду
   */
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

  /**
   * Выслать ссылку сброса пароля на эл. почту
   */
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

  /** 
   * Подтвердить сброс пароля пользователем, если он перешел по ссылке с хэшем корректного кода
   */
  public static function verifyResetPasswordCode($password, $code)
  {
    $purpose = 'reset_password';

    $user = self::getByEmail(request('email'));
    // try catch ? 
    $codeData = EmailConfirmation::validateCode($purpose, $user->id, $code);

    $user = self::find($codeData->user_id);

    $user->update([
      'password' => Hash::make($password)
    ]);

    EmailConfirmation::deleteForPurpose($user, $purpose);
  }

  /** 
   * Сменить адрес эл. почты, убрав подтверждение, если было, и сразу выслать код подтверждения
   */
  public static function changeEmail($newEmail)
  {
    $user = self::authUserEloquent();
    $user->update([
      'email_verified_at' => null,
      'email' => $newEmail
    ]);
    User::sendEmailVerifyCode('был изменен адрес электронной почты');
  }

  /** 
   * Сменить пароль
   */
  public static function changePassword($newPassword)
  {
    $user = self::authUserEloquent();
    if (!Hash::check(request('current_password'), $user->password)) {
      abort(401, __('validation.current_password'));
    }
    $user->update([
      'password' => Hash::make($newPassword)
    ]);

    return $user;
  }

  /**
   * Получить пользователя по полю + значению
   * 
   * @param $byRow = поле в бд, по которому будет осуществляться поиск
   * @param $value = значение поля
   */
  public static function getBy($byRow, $value)
  {
    $user = self::where($byRow, $value)->first();
    if (!$user) {
      abort(401, 'Пользователь не найден');
    }

    return $user;
  }

  /** 
   * Найти пользователя по полю 'email'
   * 
   * @param $email = email пользователя
   */
  public static function getByEmail($email)
  {
    return self::getBy('email', $email);
  }
}

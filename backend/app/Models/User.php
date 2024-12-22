<?php

namespace App\Models;

use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Confirmation;
use App\Traits\CanUseTableNameStatically;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable, HasFactory, CanUseTableNameStatically;

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
    'permissions' => 'array',
    'password' => 'hashed',
    'email_verified_at' => 'datetime',
  ];

  /**
   * The attributes for which you can use filters in url.
   *
   * @var array
   */
  protected $allowedFilters = [
    'id' => Where::class,
    'name' => Like::class,
    'email' => Like::class,
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

  protected $appends = ['confirmations'];

  protected function confirmations(): Attribute
  {
    $verifyEmail = !!Confirmation::purposeUser('prp_verify_email', $this->id);

    return new Attribute(get: fn() => [
      'verify_email' => $verifyEmail || false
    ]);
  }

  public static function newFactory(): Factory
  {
    return UserFactory::new();
  }

  /**
   * Смена пароля при сбросе
   */
  public static function resetPassword($email, $hashedCode, $password)
  {
    $purpose = 'prp_reset_password';
    $user = self::where('email', $email)->first();
    throw_if(
      !$user,
      new NotFoundHttpException(__('abortions.userNotFound'))
    );

    Confirmation::validateCode($purpose, $user->id, $hashedCode);

    $user->update([
      'password' => $password
    ]);

    Confirmation::deleteForPurpose($user, $purpose);

    return true;
  }

  /** 
   * Сменить адрес эл. почты, убрав подтверждение, если было, и сразу выслать код подтверждения
   */
  public static function changeEmail($newEmail)
  {
    $user = auth()->user();
    if (!$user)
      abort(404, __('abortions.userNotFound'));

    $wasVerified = $user->email_verified_at;
    $user->update([
      'email_verified_at' => null,
      'email' => $newEmail
    ]);

    return ['wasVerified' => $wasVerified];
  }

  /** 
   * Сменить пароль
   */
  public static function changePassword($newPassword)
  {
    $user = auth()->user();
    if (!$user)
      abort(404, __('abortions.userNotFound'));

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

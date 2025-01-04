<?php

namespace App\Models;

use App\DTO\Auth\AuthDTOCollection;
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
    'email_verified_at',
    'telegram',
    'telegram_chat_id',
    'password',
    'phone_number',
    'location',
    'street',
    'house',
    'role',
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
    'telegram_verified_at' => 'datetime',
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
    'telegram' => Like::class,
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
    'telegram',
    'updated_at',
    'created_at',
  ];

  protected $appends = ['confirmations'];

  protected function confirmations(): Attribute
  {
    if (!$this)
      return new Attribute(get: fn() => []);

    $verifyEmail = !!Confirmation::purposeUser('prp_verify_email', $this->id)->first();

    return new Attribute(get: fn() => [
      'verify_email' => $verifyEmail || false
    ]);
  }

  public static function newFactory(): Factory
  {
    return UserFactory::new();
  }

  /**
   * Смена пароля. 
   * 
   * Должен вызываться уже после проверки кода, если пользователь сам запросил сброс пароля
   */
  public function updatePassword($password)
  {
    $this->update([
      'password' => $password
    ]);

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
   * Получить пользователя по полю + значению, либо выдать 404 ошибку, если пользователь не найден
   * 
   * @param $byRow = поле в бд, по которому будет осуществляться поиск
   * @param $value = значение поля
   */
  public static function getBy($byRow, $value): static
  {
    $user = self::where($byRow, $value)->first();
    throw_if(
      !$user,
      new NotFoundHttpException(__('abortions.userNotFound'))
    );

    return $user;
  }

  /**
   * Получить пользователя по логину (возможные логины зарегистрированы в AuthDTOCollection)
   * 
   * если пользователь не найден - выдать ошибку
   */
  public static function getByLogin(string $login): static
  {
    $user = null;
    foreach (AuthDTOCollection::getAllDTOs() as $dto) {
      if ($user)
        break;

      $columnName = $dto->columnName;
      $user = User::where($columnName, $login)->first();
    }
    throw_if(
      !$user,
      new NotFoundHttpException(__('abortions.userNotFound'))
    );

    return $user;
  }
}

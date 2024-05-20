<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'priority'
  ];

  public static function getPriorty($name)
  {
    $arr = config('constants.roles');
    array_key_exists($name, $arr) ? $arr[$name] : false;
  }

  public static function isAllowedAll(User $user)
  {
    return self::getPriority($user->role) === 1;
  }

  public static function isAdmin(User $user)
  {
    return $user->role === 'ADMINISTRATOR';
  }

  public static function isManager(User $user)
  {
    return $user->role === 'MANAGER';
  }

  public static function isUser(User $user)
  {
    return $user->role === 'USER';
  }
}

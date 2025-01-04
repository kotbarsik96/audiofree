<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class RoleUser extends BaseModel
{
  use HasFactory;

  protected $table = 'role_users';

  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'role_id'
  ];
}

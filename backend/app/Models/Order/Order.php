<?php

namespace App\Models\Order;

use App\Models\BaseModel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Order extends BaseModel
{
  use HasFactory;

  protected $table = 'orders';

  protected $fillable = [
    'user_id',
    'status',
    'address',
    'comment',
    'name',
    'email',
    'phone_number',
  ];

  public function getSelect()
  {
    return [
      'id',
      'status',
      'address',
      'comment',
      'name',
      'email',
      'phone_number',
      'created_at',
      'updated_at'
    ];
  }

  public function scopeForUser(Builder $query, $userId, $orderId = null)
  {
    $user = User::authUser();
    if (!Role::isAdmin($user)) {
      if ($user->id !== $userId)
        abort(401, __('abortions.unauthorized'));
    }

    $query->select($this->getSelect())
      ->where('user_id', $userId);

    if ($orderId)
      $query->where('id', $orderId);
  }

  public function cancel()
  {
    $this->update([
      'status' => 'canceled'
    ]);
  }
}

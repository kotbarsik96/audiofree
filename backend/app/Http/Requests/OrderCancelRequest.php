<?php

namespace App\Http\Requests;

use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class OrderCancelRequest extends FormRequest
{
  public User | null $user;
  public Order | null $order;

  public function __construct()
  {
    $userId = request()->user_id;
    $this->user = User::find($userId);
    if ($userId && !$this->user)
      abort(404, __('abortions.userNotFound'));
  }

  public function authorize(): bool
  {
    // отменить чужой заказ
    if ($this->user) {
      $allowed = Gate::allows('cancel-third-person-order');
      if (!$allowed)
        return false;
    }

    // отменить свой заказ (если передан валидный order_id и заказ действительно принадлежит пользователю)
    $this->order = Order::find(request()->order_id);
    if (!$this->order)
      abort(404, __('abortions.orderNotFound'));

    return $this->order->user_id === auth()->user()->id;
  }

  public function rules(): array
  {
    return [];
  }
}

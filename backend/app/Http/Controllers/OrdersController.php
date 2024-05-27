<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Order\Order;
use App\Models\Cart\Cart;
use App\Models\OrderProduct\OrderProduct;
use App\Models\User;

class OrdersController extends Controller
{
  public function store(OrderStoreRequest $request)
  {
    $validated = $request->validated();
    $cart = Cart::getCart(auth()->user()->id, $request->is_oneclick)
      ->get();

    if (count($cart) < 1)
      abort(403, __('abortions.noProductsInCart'));

    $inactive = $cart->filter(fn (Cart $cartItem) => $cartItem->status !== 'active');
    if (count($inactive) > 0)
      abort(403, __('abortions.productIsInactive', ['product' => $inactive->first()->name]));

    $takenAway = Cart::takeAwayExtra($cart);

    $order = Order::create($validated);
    foreach ($cart as $cartItem) {
      OrderProduct::create([
        'order_id' => $order->id,
        'product_id' => $cartItem->product_id,
        'variation_id' => $cartItem->variation_id,
        'quantity' => $cartItem->quantity,
        'discount' => $cartItem->discount,
        'original_price' => $cartItem->price,
        'price' => $cartItem->current_price,
      ]);
    }

    return [
      'ok' => true,
      'message' => __('general.orderAccepted'),
      'data' => [
        'takenAway' => $takenAway
      ]
    ];
  }

  public function getSingle()
  {
    $order = Order::forUser(auth()->user()->id, request()->order_id)->first();

    if (!$order)
      abort(404);

    $orderProducts = OrderProduct::forOrder($order->id)->get();

    return [
      'ok' => true,
      'data' => [
        'order' => $order,
        'products' => $orderProducts
      ]
    ];
  }

  public function getList()
  {
    $orders = Order::forUser(auth()->user()->id)->get();
    $orders = $orders->map(function (Order $order) {
      $order->items = OrderProduct::forOrder($order->id)->get();
    });

    return [
      'ok' => true,
      'data' => [
        'orders' => $orders
      ]
    ];
  }

  public function cancel()
  {
  }
}

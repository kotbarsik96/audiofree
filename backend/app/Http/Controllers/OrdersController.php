<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Requests\Order\OrderCancelRequest;
use App\Models\Order\Order;
use App\Models\Cart\Cart;
use App\Models\Order\OrderProduct;
use App\Models\Product;

class OrdersController extends Controller
{
  public function store(OrderStoreRequest $request)
  {
    $validated = $request->validated();
    $cart = Cart::getCart(auth()->user()->id, $request->is_oneclick)
      ->get();

    if (count($cart) < 1)
      abort(403, __('abortions.noProductsInCart'));

    $inactive = $cart->filter(
      fn (Cart $cartItem)
      => !$cartItem->product_id || $cartItem->status !== 'active'
    );
    if (count($inactive) > 0)
      abort(403, __('abortions.productIsInactive', ['product' => $inactive->first()->name]));

    $takenAway = Cart::takeAwayExtra($cart);

    $data = array_merge($validated, [
      'user_id' => auth()->user()->id,
      'status' => config('constants.order.statuses')[0]
    ]);
    $order = Order::create($data);
    foreach ($cart as $cartItem) {
      $product = Product::find($cartItem->product_id);
      $product->update([
        'quantity' => $product->quantity - $cartItem->quantity
      ]);

      OrderProduct::create([
        'order_id' => $order->id,
        'product_id' => $cartItem->product_id,
        'variation_id' => $cartItem->variation_id,
        'quantity' => $cartItem->quantity,
        'discount' => $cartItem->discount,
        'original_price' => $cartItem->price,
        'price' => $cartItem->current_price,
      ]);

      $cartItem->delete();
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
      abort(404, __('abortions.orderNotFound'));

    $orderProducts = OrderProduct::forOrder($order->id)->get();

    return [
      'ok' => true,
      'data' => [
        'order' => $order,
        'products' => $orderProducts
      ]
    ];
  }

  public function getProducts()
  {
    $products = OrderProduct::productsList(auth()->user()->id)->get();

    return [
      'ok' => true,
      'data' => [
        'products' => $products
      ]
    ];
  }

  public function cancel(OrderCancelRequest $request)
  {
    $request->order->cancel();

    return [
      'ok' => true,
      'message' => __('general.orderCanceled')
    ];
  }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartRequest;
use App\Models\Cart\Cart;
use App\Models\User;

class CartController extends Controller
{
  public function store(CartRequest $request)
  {
    $cartItem = Cart::byProductVariation(
      $request->product->id,
      $request->variation->id,
      $request->is_oneclick
    )->first();

    if ($cartItem) {
      $cartItem->plusOneOrMore($request->quantity, $request->variation);
    } else {
      if ($request->quantity > $request->variation->quantity)
        abort(403, __('abortions.notEnoughInStock'));

      $cartItem = Cart::create([
        'user_id' => auth()->user()->id,
        'product_id' => $request->product->id,
        'variation_id' => $request->variation->id,
        'is_oneclick' => $request->is_oneclick ?? 0,
        'quantity' => $request->quantity
      ]);
    }

    return [
      'ok' => true,
      'message' => __(
        'general.productAddedToCart',
        [
          'product' => $request->product->name,
          'variation' => $request->variation->value
        ]
      )
    ];
  }

  public function get()
  {
    $user = User::authUser();

    $isOneclick = request()->is_oneclick ?? false;
    $cart = Cart::getCart($user->id, $isOneclick)->get();
    $takenAway = Cart::takeAwayExtra($cart);

    return response([
      'ok' => true,
      'data' => [
        'cart' => $cart,
        'taken_away' => $takenAway
      ]
    ]);
  }

  public function minusOne(CartRequest $request)
  {
    $cartItem = Cart::byProductVariationOrAbort(
      $request->product->id,
      $request->variation->id,
      $request->is_oneclick
    );

    $cartItem->minusOne();

    return [
      'ok' => true
    ];
  }

  public function delete(CartRequest $request)
  {
    $cartItem = Cart::byProductVariationOrAbort(
      $request->product->id,
      $request->variation->id,
      $request->is_oneclick
    );

    $cartItem->delete();

    return [
      'ok' => true,
      'message' => __('general.productRemovedFromCart')
    ];
  }
}

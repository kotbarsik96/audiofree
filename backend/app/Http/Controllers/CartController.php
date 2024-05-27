<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartRequest;
use App\Models\Product\Cart\Cart;
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
      Cart::create([
        'user_id' => auth()->user()->id,
        'product_id' => $request->product->id,
        'variation_id' => $request->variation->id,
        'is_oneclick' => $request->is_oneclick,
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

    $cart = Cart::where('user_id', $user->id)->get();
    $takenAway = $cart->takeAwayExtra();

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
  }
}

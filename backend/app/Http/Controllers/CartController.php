<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartRequest;
use App\Models\Product;
use App\Models\Product\Cart\Cart;
use App\Models\Product\ProductVariation;
use App\Models\User;

class CartController extends Controller
{
  public function store(CartRequest $request)
  {
    $cartItem = Cart::byProductVariation($request->product->id, $request->variation->id)
      ->first();

    if ($cartItem) {
      $notEnoughInStock = $cartItem->quantity + $request->quantity
        > $request->variation->quantity;
      if ($notEnoughInStock)
        abort(400, __('abortions.notEnoughInStock'));

      $cartItem->update([
        'quantity' => $request->quantity + $cartItem->quantity
      ]);
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

  public function plusOne(CartRequest $request)
  {
    $cartItem = Cart::byProductVariationOrAbort(
      $request->product->id,
      $request->variation->id
    );

    if ($cartItem->quantity + 1 > $request->variation->quantity)
      abort(403, __('abortions.notEnoughInStock'));

    $cartItem->plusOne();

    return [
      'ok' => true
    ];
  }

  public function minusOne(CartRequest $request)
  {
    $cartItem = Cart::byProductVariationOrAbort(
      $request->product->id,
      $request->variation->id
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
      $request->variation->id
    );

    $cartItem->delete();
  }
}

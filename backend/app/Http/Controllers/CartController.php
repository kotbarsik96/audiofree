<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartRequest;
use App\Models\Cart\Cart;
use App\Models\Product;
use App\Models\Product\ProductVariation;

class CartController extends Controller
{
  public function store(CartRequest $request)
  {
    $isOneclick = $request->input('is_oneclick') ?? false;
    $cartItem = Cart::firstOrCreate(
      [
        'user_id' => auth()->user()->id,
        'variation_id' => $request->input('variation_id'),
        'is_oneclick' => $isOneclick
      ],
      ['quantity' => 0]
    );

    $cartItem->quantity = $cartItem->quantity + (int) $request->input('quantity');
    $cartItem->save();
    $variation = ProductVariation::find($request->input('variation_id'));
    $product = Product::find($variation->product_id);

    return [
      'ok' => true,
      'message' => __('general.productAddedToCart', [
        'product' => $product->name,
        'variation' => $variation->name
      ])
    ];
  }

  public function get() {}
}

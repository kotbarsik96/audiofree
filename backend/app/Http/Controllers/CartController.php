<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartRequest;
use App\Models\Cart\Cart;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function store(CartRequest $request)
  {
    $isOneclick = $request->input('is_oneclick') ?? false;
    $cartItem = Cart::firstOrNew(
      [
        'user_id' => auth()->user()->id,
        'variation_id' => $request->input('variation_id'),
        'is_oneclick' => $isOneclick
      ],
      ['quantity' => 0]
    );

    $cartItem->quantity = $cartItem->quantity + (int) $request->input('quantity');
    if ($cartItem->quantity > 0) {
      $cartItem->save();
      $variation = ProductVariation::find($request->input('variation_id'));
      $product = Product::find($variation->product_id);

      return response([
        'ok' => true,
        'message' => __('general.productAddedToCart', [
          'product' => $product->name,
          'variation' => $variation->name
        ])
      ]);
    } else {
      return $this->deleteItem($cartItem);
    }
  }

  public function get(Request $request)
  {
    return response([
      'ok' => true,
      'data' => Cart::select([
        'cart.id',
        'variation_id',
        'cart.quantity'
      ])
        ->where('user_id', auth()->user()->id)
        ->where('is_oneclick', (int) !!$request->input('is_oneclick'))
        ->with([
          'variation:id,product_id,name,image_id,price,discount,quantity',
          'variation.product:id,name',
          'variation.image:id,name,extension,path,disk'
        ])
        ->get()
    ]);
  }

  public function delete()
  {
    $item = Cart::itemOrFail();
    return $this->deleteItem($item);
  }

  public function deleteItem(Cart $item)
  {
    $variation = ProductVariation::find($item->variation_id);
    $product = Product::find($variation->product_id);
    $item->delete();

    return response([
      'ok' => true,
      'message' => __('general.productRemovedFromCart', [
        'product' => $product->name ?? '',
        'variation' => $variation->name ?? ''
      ])
    ]);
  }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartRequest;
use App\Models\Cart\Cart;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartController extends Controller
{
  public function store(CartRequest $request)
  {
    $isOneclick = $request->input('is_oneclick') ?? false;
    if ($isOneclick) {
      Cart::where('is_oneclick', 1)
        ->where('user_id', auth()->user()->id)
        ->delete();
    }
    $cartItem = Cart::firstOrNew(
      [
        'user_id' => auth()->user()->id,
        'variation_id' => $request->input('variation_id'),
        'is_oneclick' => $isOneclick
      ],
      ['quantity' => 0]
    );

    $variation = ProductVariation::find($request->input('variation_id'));
    $product = Product::find($variation->product_id);

    $cartItem->quantity = (int) $request->input('quantity');

    throw_if(
      $cartItem->quantity > $variation->quantity || $cartItem->quantity < 1,
      new BadRequestHttpException(__('abortions.wrongQuantity'))
    );

    $cartItem->save();

    return response([
      'ok' => true,
      'message' => __('general.productAddedToCart', [
        'product' => $product->name,
        'variation' => $variation->name
      ])
    ]);
  }

  public function get(Request $request)
  {
    $cart = Cart::select([
      'cart.id',
      'is_oneclick',
      'variation_id',
      'cart.quantity'
    ])
      ->where('user_id', auth()->user()->id)
      ->where('is_oneclick', (int) !!$request->input('is_oneclick'))
      ->with([
        'variation' => function ($query) {
          return $query->select(
            'id',
            'product_id',
            'name',
            'image_id',
            'price',
            'discount',
            'quantity',
            DB::raw(ProductVariation::currentPriceSelectFormula())
          );
        },
        'variation.product:id,name',
        'variation.image:id,name,extension,path,disk'
      ])
      ->get();

    return response([
      'ok' => true,
      'data' => $cart
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

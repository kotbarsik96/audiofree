<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FavoritesController extends Controller
{
  public function store(Request $request)
  {
    $variation = ProductVariation::itemOrFail($request->input('variation_id'));

    Favorite::firstOrCreate([
      'user_id' => auth()->user()->id,
      'variation_id' => $variation->id,
    ]);
    $product = Product::find($variation->product_id);

    return response([
      'ok' => true,
      'message' => __(
        'general.addedToFavorites',
        [
          'product' => $product->name,
          'variation' => $variation->name
        ]
      ),
    ]);
  }

  public function get()
  {
    return response([
      'ok' => true,
      'data' => Favorite::where('user_id', auth()->user()->id)
        ->with([
          'variation:id,name,image_id,price,discount,quantity,product_id',
          'variation.product:id,name',
          'variation.image:id,name,extension,path,alt,disk',
        ])
        ->get()
    ]);
  }

  public function delete(Request $request)
  {
    $variation = ProductVariation::itemOrFail($request->input('variation_id'));

    Favorite::firstOrNew([
      'user_id' => auth()->user()->id,
      'variation_id' => $variation->id
    ])->delete();

    $product = Product::find($variation->product_id);

    return response([
      'ok' => true,
      'message' => __('general.removedFromFavorites', [
        'product' => $product->name,
        'variation' => $variation->name
      ])
    ]);
  }
}

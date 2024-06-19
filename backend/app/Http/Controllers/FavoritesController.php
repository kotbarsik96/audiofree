<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product\ProductVariation;

class FavoritesController extends Controller
{
  public function store()
  {
    $userId = auth()->user()->id;
    $variationId = request()->variation_id;

    $variation = ProductVariation::find($variationId);
    if(!$variation)
      abort(404, __('abortions.variationNotFound2'));

    $item = Favorite::where('user_id', $userId)
      ->where('variation_id', $variationId)
      ->first();
    if ($item)
      abort(400, __('abortions.alreadyInFavorites'));

    Favorite::create([
      'user_id' => $userId,
      'variation_id' => $variationId
    ]);

    return [
      'ok' => true,
    ];
  }

  public function get()
  {
    $favorites = Favorite::list(auth()->user()->id)->get();

    return [
      'ok' => true,
      'favorites' => $favorites
    ];
  }

  public function delete()
  {
    $userId = auth()->user()->id;
    $variationId = request()->variation_id;

    $item = Favorite::where('user_id', $userId)
      ->where('variation_id', $variationId)
      ->first();
    if (!$item)
      abort(404, __('general.notInFavorites'));

    $item->delete();

    return [
      'ok' => true
    ];
  }
}

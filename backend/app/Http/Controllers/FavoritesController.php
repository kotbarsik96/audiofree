<?php

namespace App\Http\Controllers;

use App\Models\Favorite;

class FavoritesController extends Controller
{
  public function store()
  {
    $userId = request()->user->id;
    $prodId = request()->product_id;
    $variationId = request()->variation_id;

    $item = Favorite::where('user_id', $userId)
      ->where('product_id', $prodId)
      ->where('variation_id', $variationId)
      ->first();
    if ($item)
      abort(400, __('abortions.alreadyInFavorites'));

    Favorite::create([
      'user_id' => $userId,
      'product_id' => $prodId,
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
    $userId = request()->user->id;
    $prodId = request()->product_id;
    $variationId = request()->variation_id;

    $item = Favorite::where('user_id', $userId)
      ->where('product_id', $prodId)
      ->where('variation_id', $variationId)
      ->first();
    if (!$item)
      abort(404, __('general.notInFavorites'));

    return [
      'ok' => true
    ];
  }
}

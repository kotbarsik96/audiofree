<?php

namespace App\Http\Controllers;

use App\Models\Cart\Cart;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Models\User;
use App\Validations\AuthValidation;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function edit(Request $request)
  {
    $validated = $request->validate([
      'name' => AuthValidation::userField(),
      'phone_number' => AuthValidation::phoneNumber(),
      'location' => AuthValidation::userField(),
      'street' => AuthValidation::userField(),
      'house' => AuthValidation::userField()
    ]);


    if (!$user = auth()->user()) {
      return response([
        'message' => __('general.authFailed')
      ], 401);
    }

    $user = User::find($user->id);
    $user->update($validated);

    return response()->json([
      'message' => __('general.dataUpdated'),
      'ok' => true,
      'data' => [
        'user' => $user
      ]
    ]);
  }

  public function transformProductCollection($collection)
  {
    return $collection->transform(function ($item) {
      return [
        'product_id' => $item->variation->product_id,
        'variation_id' => $item->variation->id,
      ];
    });
  }

  public function getProductsCollections()
  {
    $userId = auth()->user()->id;
    $cart = $this->transformProductCollection(Cart::select('variation_id')
      ->where('user_id', $userId)
      ->where('is_oneclick', false)
      ->with(['variation:id,product_id' => [
        'product:id'
      ]])
      ->get());
    $favorites = $this->transformProductCollection(Favorite::select('variation_id')
      ->where('user_id', $userId)
      ->get());

    return response([
      'ok' => true,
      'data' => [
        'cart' => $cart,
        'favorites' => $favorites
      ]
    ]);
  }
}

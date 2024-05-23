<?php

namespace App\Models\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
  use HasFactory;

  protected $table = 'products_rating';

  protected $fillable = [
    'product_id',
    'user_id',
    'value'
  ];

  public static function getOrAbort($productId, $userId)
  {
    $rating = self::where('product_id', $productId)
      ->where('user_id', $userId)
      ->first();

    if (!$rating) abort(404, __('abortions.userDidNotSetProductRating'));

    return $rating;
  }

  public static function setOrUpdate(Product $product, int $value)
  {
    $user = auth()->user();

    $ratingInfo = self::where('product_id', $product->id)
      ->where('user_id', $user->id)
      ->first();
    if ($ratingInfo && $ratingInfo->value !== $value) {
      $ratingInfo->update([
        'value' => $value
      ]);
    } else {
      self::create([
        'product_id' => $product->id,
        'user_id' => $user->id,
        'value' => $value
      ]);
    }
  }

  public static function removeRating(Product $product)
  {
    $user = auth()->user();

    $ratingInfo = self::where('product_id', $product->id)
      ->where('user_id', $user->id)
      ->first();

    if ($ratingInfo) $ratingInfo->delete();
  }
}

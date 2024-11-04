<?php

namespace App\Models\Product;

use App\Models\Product;
use App\Models\User;
use Database\Factories\Product\ProductRatingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
  use HasFactory;

  protected $table = 'products_rating';

  protected $fillable = [
    'product_id',
    'user_id',
    'value',
    'pros',
    'cons',
    'description'
  ];

  public static function newFactory(): Factory
  {
    return ProductRatingFactory::new();
  }

  public static function getOrAbort($productId, $userId)
  {
    $rating = self::where('product_id', $productId)
      ->where('user_id', $userId)
      ->first();

    if (!$rating) abort(404, __('abortions.userDidNotSetProductRating'));

    return $rating;
  }

  public static function setOrUpdate(Product $product, iterable $data)
  {
    $user = auth()->user();

    ProductRating::updateOrCreate(
      [
        'product_id' => $product->id,
        'user_id' => $user->id
      ],
      $data
    );
  }

  public static function removeRating(Product $product)
  {
    $user = auth()->user();

    self::where('product_id', $product->id)
      ->where('user_id', $user->id)
      ->delete();
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function scopeForProduct(Builder $query, int $productId)
  {
    return $query->where('product_id', $productId)
      ->with('user:id,name');
  }
}

<?php

namespace App\Models;

use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Favorite extends Model
{
  use HasFactory;

  protected $table = 'favorites';

  protected $fillable = [
    'user_id',
    'variation_id'
  ];

  protected $casts = [
    'current_price' => 'integer',
  ];

  public function scopeList(Builder $query, $userId)
  {
    $query->select([
      'favorites.variation_id',
      'products.name',
      'products.status',
      'products.brand',
      'products.category',
      'products.type',
      'product_variations.product_id',
      'product_variations.value',
      'product_variations.price',
      'product_variations.discount',
      DB::raw(ProductVariation::getCurrentPriceQuery()),
      'product_variations.image_path',
      'product_variations.quantity',
    ])->where('user_id', $userId)
      ->leftJoin('product_variations', 'product_variations.id', '=', 'favorites.variation_id')
      ->leftJoin('products', 'products.id', '=', 'product_variations.product_id');
  }
}

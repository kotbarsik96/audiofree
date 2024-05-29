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
    'product_id',
    'variation_id'
  ];

  public function scopeList(Builder $query, $userId)
  {
    $query->select([
      'favorites.product_id',
      'favorites.variation_id',
      'products.name',
      'products.status',
      'products.brand',
      'products.category',
      'products.type',
      'product_variation_values.value',
      'product_variation_values.price',
      'product_variation_values.discount',
      DB::raw(ProductVariation::getCurrentPriceQuery()),
      'product_variation_values.image_path',
      'product_variation_values.quantity',
    ])->where('user_id', $userId)
      ->leftJoin('products', 'products.id', '=', 'favorites.product_id')
      ->leftJoin('products', 'product_variation_values.id', '=', 'favorites.variation_id');
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\FilterableModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Product extends FilterableModel
{
  use HasFactory;

  protected $fillable = [
    'name',
    'price',
    'discount',
    'description',
    'status',
    'brand',
    'category',
    'type',
    'image_path',
    'created_by',
    'updated_by'
  ];

  protected $casts = [
    'price' => 'integer',
    'discount' => 'integer',
    'current_min_price' => 'float',
    'quantity' => 'integer',
    'rating' => 'integer'
  ];

  public static function getOrAbort($productId)
  {
    $product = self::find($productId);
    if (!$product) abort(404, __('abortions.productNotFound'));
    return $product;
  }

  public function getImagePath()
  {
    return 'products/' . $this->id . '/';
  }

  public static function allowsStore(Product | null $product = null)
  {
    if (!$product) $product = Product::find(request()->product_id);
    $allows = $product
      ? Gate::allows('update-product', $product)
      : Gate::allows('create-product');
    return $allows;
  }

  public function scopeCatalog(Builder $query, $statuses = ['active'])
  {
    $query->select([
      'products.id',
      'products.name',
      'products.category',
      'products.type',
      DB::raw('AVG(products_rating.value) as rating'),
      // DB::raw('MIN(product_variation_values.price) as price')
    ])->whereIn('status', $statuses)
      ->leftJoin('products_rating', 'products_rating.product_id', '=', 'products.id')
      // ->leftJoin('product_variation_values', 'products.id', '=', 'product_variation_values.product_id')
      ->groupBy('products.id');
  }

  public function scopeForPage(Builder $query, $productId)
  {
    $query->catalog()
      ->addSelect(['products.status', 'products.description'])
      ->where('products.id', $productId);
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\FilterableModel;
use Illuminate\Support\Facades\Gate;

class Product extends FilterableModel
{
  use HasFactory;

  protected $fillable = [
    'name',
    'price',
    'discount_price',
    'quantity',
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
    'discount_price' => 'integer',
    'quantity' => 'integer'
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
}

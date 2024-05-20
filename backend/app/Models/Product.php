<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Product extends Model
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
    'image_path'
  ];

  public function getImagePath()
  {
    return 'products/' . $this->id . '/';
  }

  public static function allowsStore(Product | null $product = null)
  {
    if(!$product) $product = Product::find(request()->product_id);
    $allows = $product
      ? Gate::allows('update-product', $product)
      : Gate::allows('create-product');
    return $allows;
  }
}

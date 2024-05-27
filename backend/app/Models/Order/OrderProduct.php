<?php

namespace App\Models\OrderProduct;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderProduct extends Model
{
  use HasFactory;

  protected $table = 'order_products';

  protected $fillable = [
    'order_id',
    'product_id',
    'variation_id',
    'quantity',
    'discount',
    'original_price',
    'price'
  ];

  public function scopeForOrder(Builder $query, $orderId)
  {
    $query->select([
      'order_id',
      'product_id',
      'variation_id',
      'discount',
      'original_price',
      'price',
    ])->where('order_id', $orderId);
  }
}

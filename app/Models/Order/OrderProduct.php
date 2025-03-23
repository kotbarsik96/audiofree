<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_variation_id',
        'product_name',
        'product_quantity',
        'product_price',
        'product_total_cost',
    ];

    protected $table = 'orders_products';
}

<?php

namespace App\Models\Order;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'user_id',
        'orderer_data',
        'delivery_place',
        'delivery_address',
        'order_status',
        'desired_payment_type',
        'is_paid',
        'image',
    ];

    protected $table = 'orders';

    protected $casts = [
        'orderer_data' => 'array'
    ];
}

<?php

namespace App\Models\Order;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * @param \Illuminate\Database\Eloquent\Collection<\App\Models\Product\ProductVariation> $cartItems
     */
    public static function createCollage(Collection $productVariations)
    {
        $images = $productVariations->slice(0, 4);

        return $images;
    }
}

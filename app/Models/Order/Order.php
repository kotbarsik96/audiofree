<?php

namespace App\Models\Order;

use App\Models\Product\ProductVariation;
use App\Services\Image\CollageService;
use App\Traits\Filterable;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Orchid\Attachment\Models\Attachment;

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
        'image_id',
    ];

    protected $table = 'orders';

    protected $casts = [
        'orderer_data' => 'array',
        'total_cost' => 'integer'
    ];

    public function image()
    {
        return $this->hasOne(
            Attachment::class,
            'id',
            'image_id'
        )->withDefault();
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection<\App\Models\Product\ProductVariation> $cartItems
     */
    public function createCollage(Collection $productVariations)
    {
        $images = $productVariations->slice(0, 4)
            ->map(
                fn(ProductVariation $pv)
                => $pv->image()->first()?->url()
            )
            ->filter(fn($url) => !!$url);

        $attachment = (new CollageService(
            $images->toArray(),
            config('constants.paths.images.orders'),
            "order-$this->id",
            new ImageManager(Driver::class),
            config('constants.order.image_group')
        ))->createCollage();

        $this->update(['image_id' => $attachment->id]);

        return $this;
    }

    public function scopeForList($query)
    {
        return $query->addSelect([
            'orders.id',
            'orders.orderer_data',
            'orders.delivery_place',
            'orders.delivery_address',
            'orders.order_status',
            'orders.desired_payment_type',
            'orders.is_paid',
            'orders.image_id',
            'orders.created_at',
            'orders.updated_at',
        ]);
    }

    public function scopeTotalCost($query)
    {
        return $query->withSum('products as total_cost', 'product_total_cost');
    }
}

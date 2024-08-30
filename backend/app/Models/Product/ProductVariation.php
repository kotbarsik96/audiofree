<?php

namespace App\Models\Product;

use App\Models\Product;
use App\Models\Traits\HandleOrchidAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Orchid\Attachment\Attachable;

class ProductVariation extends Model
{
  use HasFactory, Attachable, HandleOrchidAttachments;

  protected $fillable = [
    'product_id',
    'price',
    'discount',
    'image_path',
    'quantity',
    'name',
    'created_by',
    'updated_by',
  ];

  protected $casts = [
    'price' => 'integer',
    'discount' => 'integer',
    'current_price' => 'integer',
    'quantity' => 'integer',
  ];

  protected $table = 'product_variations';

  public function scopeGetCurrentPriceQuery()
  {
    return "{$this->table}.price - ({$this->table}.price / 100 * {$this->table}.discount) as current_price";
  }

  public static function getByName($productId, $varName)
  {
    return self::where('product_id', $productId)
      ->where('name', $varName)
      ->first();
  }

  public static function getByNameOrAbort($productId, $varName)
  {
    $variation = self::getByName($productId, $varName);
    if (!$variation)
      abort(404, __('abortions.variationNotFound', ['name' => $varName]));
    return $variation;
  }

  public function deleteVariation()
  {
    $this->deleteGallery();
    $this->delete();
  }

  public function scopeForProduct(Builder $query, $productId)
  {
    $variations = $query
      ->select([
        'id',
        'name',
        'price',
        'discount',
        'quantity',
        DB::raw($this->getCurrentPriceQuery()),
        'product_variations.image_path',
      ])
      ->where('product_id', $productId)->get();

    foreach ($variations as $variation) {
      $variation->gallery = $variation->getGallery();
    }

    return $variations;
  }

  public function scopeMinPriceAndDiscount(Builder $query, $productId)
  {
    $query->select([
      'price',
      'discount',
      DB::raw($this->getCurrentPriceQuery())
    ])->where('product_variations.product_id', $productId)
      ->orderBy('current_price');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }

  public function gallery()
  {
    return $this->attachment(
      config('constants.product.variation.gallery_group')
    );
  }
}

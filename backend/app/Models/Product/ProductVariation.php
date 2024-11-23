<?php

namespace App\Models\Product;

use App\Models\Product;
use Database\Factories\Product\ProductVariationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductVariation extends Model
{
  use HasFactory, Attachable;

  protected $fillable = [
    'product_id',
    'image_id',
    'price',
    'discount',
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

  public static function newFactory(): Factory
  {
    return ProductVariationFactory::new();
  }

  public static function transformToSetCurrentPrice($collection)
  {
    return $collection->transform(function ($item) {
      $item->variation->current_price = Product::priceWithDiscount(
        $item->variation->price,
        $item->variation->discount
      );
      return $item;
    });
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

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }

  public function detachAndDelete()
  {
    $this->delete();
  }

  public function image()
  {
    return $this->hasOne(Attachment::class, 'id', 'image_id')->withDefault();
  }

  public function gallery()
  {
    return $this->attachment(
      config('constants.product.variation.gallery_group')
    );
  }

  public static function itemOrFail($variationId)
  {
    $variation = self::find($variationId);
    throw_if(!$variation, new NotFoundHttpException(__('abortions.variationNotFound2')));

    return $variation;
  }
}

<?php

namespace App\Models\Product;

use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryImage;
use App\Models\Image;
use App\Models\Product;
use App\Models\Traits\HandleOrchidAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
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
    'value',
    'created_by',
    'updated_by',
  ];

  protected $casts = [
    'price' => 'integer',
    'discount' => 'integer',
    'current_price' => 'integer',
    'quantity' => 'integer',
  ];

  protected $table = 'product_variation_values';

  public function scopeGetCurrentPriceQuery()
  {
    return "{$this->table}.price - ({$this->table}.price / 100 * {$this->table}.discount) as current_price";
  }

  public static function getByValue($productId, $variationValue)
  {
    return self::where('product_id', $productId)
      ->where('value', $variationValue)
      ->first();
  }

  public static function getByValueOrAbort($productId, $variationValue)
  {
    $variation = self::getByValue($productId, $variationValue);
    if (!$variation)
      abort(404, __('abortions.variationNotFound', ['value' => $variationValue]));
    return $variation;
  }

  public function deleteVariation()
  {
    $this->deleteGallery();
    $this->delete();
  }

  public static function createOrUpdate(Product $product, $data)
  {
    if (!array_key_exists('value', $data))
      abort(400, __('abortions.variationValueNotSpecified'));

    $variation = self::where('product_id', $product->id)
      ->where('value', $data['value'])
      ->first();
    if ($variation && $data)
      $variation->update($data);
    else {
      $variation = ProductVariation::create(
        array_merge($data, ['product_id' => $product->id])
      );
    }

    return $variation;
  }

  public function getGallery()
  {
    $gallery = Gallery::where('variation_id', $this->id)->first();

    if (!$gallery)
      return [];

    return GalleryImage::select(['image_path', 'order'])
      ->where('gallery_id', $gallery->id)
      ->get();
  }

  public function createOrUpdateGallery()
  {
    $gallery = Gallery::where('variation_id', $this->id)->first();

    if ($gallery && $gallery->name !== $this->value)
      $gallery->update(['name' => $this->value]);
    elseif (!$gallery) {
      $gallery = Gallery::create([
        'name' => $this->value,
        'variation_id' => $this->id
      ]);
    }

    return $gallery;
  }

  public static function uploadImage(Product $product, UploadedFile $image)
  {
    $path = $product->getImagePath();
    return Image::upload($image, $path);
  }

  public function uploadGallery(array $images, Product $product)
  {
    $path = $product->getImagePath();
    $gallery = $this->createOrUpdateGallery();

    foreach ($images as $img) {
      $storedImg = Image::upload($img, $path);
      GalleryImage::create([
        'gallery_id' => $gallery->id,
        'image_path' => $storedImg->path
      ]);
    }
  }

  public function deleteGallery()
  {
    $gallery = Gallery::where('variation_id', $this->id)->first();
    if (!$gallery)
      return;

    $imagesToDelete = self::whereIn('path', function (Builder $query) use ($gallery) {
      $query->select('image_path')->from('galleries_images')
        ->where('gallery_id', $gallery->id);
    })->get();

    foreach ($imagesToDelete as $image) {
      self::deleteImage($image);
    }
  }

  public function scopeForProduct(Builder $query, $productId)
  {
    $variations = $query
      ->select([
        'id',
        'value',
        'price',
        'discount',
        'quantity',
        DB::raw($this->getCurrentPriceQuery()),
        'product_variation_values.image_path',
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
    ])->where('product_variation_values.product_id', $productId)
      ->orderBy('current_price');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
}

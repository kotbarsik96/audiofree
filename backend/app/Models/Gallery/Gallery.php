<?php

namespace App\Models\Gallery;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'product_id'
  ];

  public static function getProductGallery($productId)
  {
    $gallery = self::where('product_id', $productId)->first();
    if ($gallery)
      return $gallery;

    $product = Product::find($productId);
    if (!$product) {
      abort(400, __('abortions.productAndGalleryNotFound'));
    }

    return self::create([
      'product_id' => $productId
    ]);
  }

  public static function uploadForProduct(array $images, Product $product)
  {
    $path = $product->getImagePath();
    $gallery = self::getProductGallery($product->id);

    foreach ($images as $img) {
      $storedImg = Image::upload($img, $path);
      GalleryImage::create([
        'gallery_id' => $gallery->id,
        'image_path' => $storedImg->path
      ]);
    }
  }
}

<?php

namespace App\Models\Product;

use App\Helpers\AppHelper;
use App\Models\BaseModel;
use App\Models\Product;
use Database\Factories\Product\ProductInfoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductInfo extends BaseModel
{
  use HasFactory;

  protected $table = 'products_info';

  protected $fillable = [
    'product_id',
    'name',
    'value'
  ];

  public static function newFactory(): Factory
  {
    return ProductInfoFactory::new();
  }

  public static function removeNotInRequest(array $info, Product $product)
  {
    $collection = self::where('product_id', $product->id)->get();
    foreach ($collection as $item) {
      $isInRequest = AppHelper::array_find(
        $info,
        fn ($infoItem) => $infoItem['name'] === $item->name
      );
      if (!$isInRequest)
        $item->delete();
    }
  }

  public static function storeFromRequest(array $info, Product $product)
  {
    foreach ($info as $infoItem) {
      $existsItem = self::where('product_id', $product->id)
        ->where('name', $infoItem['name'])->first();
      if ($existsItem && $existsItem->value !== $infoItem['value']) {
        $existsItem->update([
          'value' => $infoItem['value']
        ]);
      } elseif (!$existsItem) {
        self::create([
          'product_id' => $product->id,
          'name' => $infoItem['name'],
          'value' => $infoItem['value']
        ]);
      }
    }
  }

  public function scopeForProduct(Builder $query, $productId)
  {
    $query
      ->select(['name', 'value'])
      ->where('product_id', $productId);
  }
}

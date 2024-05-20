<?php

namespace App\Models\Product;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductVariation extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'value',
    'product_id',
    'active'
  ];

  protected $table = 'products_variations';

  public static function removeNotInRequest(array $variations, $productId)
  {
    $collection = self::where('product_id', $productId)->get();
    foreach ($collection as $item) {
      $values = AppHelper::array_find($variations, fn ($data) => $data['name'] === $item->name, []);
      if (!in_array($item->value, $values ?? [])) {
        $item->update([
          'active' => false
        ]);
      }
    }
  }

  public static function storeFromRequest(array $variations, $productId)
  {
    foreach ($variations as $data) {
      $name = $data['name'];
      $values = $data['values'];

      foreach ($values as $value) {
        $data = [
          'name' => $name,
          'value' => $value,
          'product_id' => $productId
        ];

        $exists = ProductVariation::where($data)->first();

        if (!$exists) {
          ProductVariation::create(array_merge($data, ['active' => true]));
        } elseif (!$exists->active) {
          $exists->update([
            'active' => true
          ]);
        }
      }
    }
  }

  public function scopeNotInManyToMany(Builder $query)
  {
    // $query->whereNotIn('id', function(Builder $subquery) {
    //   $subquery->join()
    // });
  }
}

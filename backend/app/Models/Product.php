<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use App\Traits\Filterable;
use Database\Factories\Product\ProductFactory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

class Product extends FilterableModel
{
  use HasFactory, AsSource, Attachable, Filterable;

  protected $fillable = [
    'name',
    'image_id',
    'description',
    'status_id',
    'brand_id',
    'category_id',
    'type_id',
    'created_by',
    'updated_by',
  ];

  protected $casts = [
    'price' => 'integer',
    'discount' => 'integer',
    'current_min_price' => 'float',
    'current_price' => 'integer',
    'quantity' => 'integer',
    'rating' => 'integer'
  ];

  public static function newFactory(): Factory
  {
    return ProductFactory::new();
  }

  public function deleteAndAlert()
  {
    $this->delete();

    Alert::info(__('orchid.success'));
  }

  public function variations()
  {
    return $this->hasMany(ProductVariation::class, 'product_id');
  }

  public function info()
  {
    return $this->hasMany(ProductInfo::class, 'product_id');
  }

  public function updateInfo(array $newInfo = null)
  { 
    $newNames = collect($newInfo)->pluck('name')->toArray();

    ProductInfo::where('product_id', $this->id)
      ->whereNotIn('name', $newNames)
      ->delete();

    foreach($newInfo as $item) {
      ProductInfo::updateOrCreate([
        'product_id' => $this->id,
        'name' => $item['name'],
        'value' => $item['value'],
      ]);
    }
  }

  public function image()
  {
    return $this->hasOne(Attachment::class, 'id', 'image_id')->withDefault();
  }

  public function rating()
  {
    return $this->hasMany(ProductRating::class, 'product_id');
  }
}

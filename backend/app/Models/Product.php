<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use App\Models\Taxonomy\TaxonomyValue;
use App\Traits\Filterable;
use Database\Factories\Product\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

class Product extends Model
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
    'quantity' => 'integer',
    'rating' => 'float',
    'min_price' => 'float',
    'max_price' => 'float',
  ];

  public static function priceWithDiscountFormula()
  {
    return 'product_variations.price - (product_variations.price / 100 * product_variations.discount)';
  }

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

  public function firstVariation()
  {
    return $this->hasOne(ProductVariation::class, 'product_id');
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

    foreach ($newInfo as $item) {
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

  public function scopeActiveStatus(Builder $query)
  {
    $status = TaxonomyValue::where('slug', 'product_status')
      ->where('value_slug', 'active')
      ->first();

    return $query->where('status_id', $status->id);
  }

  public function status()
  {
    return $this->hasOne(TaxonomyValue::class, 'id', 'status_id');
  }

  public function brand()
  {
    return $this->hasOne(TaxonomyValue::class, 'id', 'brand_id');
  }

  public function scopeMinPrice(Builder $query)
  {
    return $query->addSelect(
      DB::raw('MIN(' . self::priceWithDiscountFormula() . ') as min_price')
    )
      ->join('product_variations', 'product_variations.product_id', '=', 'products.id')
      ->groupBy('products.id');
  }
  public function scopeMaxPrice(Builder $query)
  {
    return $query->addSelect(
      DB::raw('MAX(' . self::priceWithDiscountFormula() . ') as max_price')
    )
      ->join('product_variations', 'product_variations.product_id', '=', 'products.id')
      ->groupBy('products.id');
  }
  public function scopeMinAndMaxPrice(Builder $query)
  {
    return $query
      ->addSelect(
        'products.id',
        DB::raw('MIN(' . self::priceWithDiscountFormula() . ') as min_price'),
        DB::raw('MAX(' . self::priceWithDiscountFormula() . ') as max_price'),
      )
      ->join('product_variations', 'product_variations.product_id', '=', 'products.id')
      ->groupBy('products.id');
  }
}

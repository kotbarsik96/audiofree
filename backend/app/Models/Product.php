<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\FilterableModel;
use App\Models\Product\ProductVariation;
use App\Models\Traits\HandleOrchidAttachments;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

class Product extends FilterableModel
{
  use HasFactory, AsSource, Attachable, HandleOrchidAttachments;

  protected $fillable = [
    'name',
    'price',
    'discount',
    'description',
    'status',
    'brand',
    'category',
    'type',
    'image_path',
    'created_by',
    'updated_by'
  ];

  protected $casts = [
    'price' => 'integer',
    'discount' => 'integer',
    'current_min_price' => 'float',
    'current_price' => 'integer',
    'quantity' => 'integer',
    'rating' => 'integer'
  ];

  public function getImagePath()
  {
    return 'products/' . $this->id . '/';
  }

  public static function allowsStore(Product | null $product = null)
  {
    if (!$product) $product = Product::find(request()->product_id);
    $allows = $product
      ? Gate::allows('update-product', $product)
      : Gate::allows('create-product');
    return $allows;
  }

  public function scopeCatalog(Builder $query)
  {
    $query->select([
      'products.id',
      'products.name',
      'products.category',
      'products.type',
      DB::raw('AVG(products_rating.value) as rating'),
    ])
      ->leftJoin('products_rating', 'products_rating.product_id', '=', 'products.id')
      ->groupBy('products.id');
  }

  public function scopeOnlyInStock(Builder $query)
  {
    $query->addSelect([
      DB::raw('MAX(product_variations.quantity) as max_quantity'),
    ])->leftJoin('product_variations', 'product_variations.product_id', '=', 'products.id')
      ->having('max_quantity', '>', 0);
  }

  public function scopeForPage(Builder $query, $productId)
  {
    $query->catalog()
      ->addSelect(['products.status', 'products.description'])
      ->where('products.id', $productId);
  }

  public function deleteAndAlert()
  {
    $this->detachAll();
    $this->delete();

    Alert::info(__('orchid.success'));
  }

  public function variations()
  {
    return $this->hasMany(ProductVariation::class, 'product_id');
  }
}

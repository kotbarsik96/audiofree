<?php

namespace App\Models\Taxonomy;

use App\Filters\QueryFilter;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
  use HasFactory;

  protected $fillable = [
    'slug',
    'name',
    'group'
  ];

  protected $table = 'taxonomies';

  public function values()
  {
    return $this->hasMany(TaxonomyValue::class, 'slug', 'slug');
  }

  public function scopeFilters(Builder $query)
  {
    return $query->whereIn('slug', [
      'brand',
      'category',
      'type',
      'product_status'
    ]);
  }

  public static function mapFilters($filters)
  {
    $filters = $filters->map(function ($item) {
      switch ($item->slug) {
        case 'brand':
          $item->type = 'checkbox';
          break;
        case 'category':
          $item->type = 'checkbox';
          break;
        case 'type':
          $item->type = 'radio';
          break;
        case 'product_status':
          $item->type = 'checkbox';
          break;
      }

      return $item;
    });

    $filters->push(self::getPricesFilter());

    $filters->push([
      'type' => 'checkbox',
      'slug' => 'has_discount',
      'name' => 'Скидка',
      'values' => [
        [
          'value' => 'Есть скидка',
          'value_slug' => true
        ],
        [
          'value' => 'Нет скидки',
          'value_slug' => false
        ],
      ],
    ]);

    return $filters;
  }

  public static function getPricesFilter()
  {
    $prices = Product::select([
      'min_price' => Product::minPrice()
        ->orderBy('min_price')
        ->limit(1),
      'max_price' => Product::maxPrice()
        ->orderByDesc('max_price')
        ->limit(1)
    ])
      ->filter(new QueryFilter(request()))
      ->first();

    return [
      'type' => 'range',
      'slug' => 'price',
      'name' => 'Цена',
      'min' => $prices->min_price,
      'max' => $prices->max_price,
    ];
  }
}

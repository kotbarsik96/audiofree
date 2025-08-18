<?php

namespace App\DTO;

use App\Filters\ProductFilter;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductFilterRangeDTO
{
  public $type = 'range';

  public function __construct(
    public string $slug,
    public string $name,
    public int | null $min,
    public int | null $max,
  ) {
  }

  public static function loadPrice(Request $request)
  {
    $filter = new ProductFilter($request);

    $prices = Product::select([
      'min_price' => Product::minPrice()
        ->filter($filter->excludeQueries(['min_price', 'max_price']))
        ->orderBy('min_price')
        ->limit(1),
      'max_price' => Product::maxPrice()
        ->filter($filter->excludeQueries(['min_price', 'max_price']))
        ->orderByDesc('max_price')
        ->limit(1),
    ])
      ->first();

    return new static(
      'price',
      'Цена',
      $prices->min_price,
      $prices->max_price
    );
  }
}
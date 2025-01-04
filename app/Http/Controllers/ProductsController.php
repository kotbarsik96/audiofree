<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\ProductRatingRequest;
use App\Models\Product;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use App\Models\Taxonomy\Taxonomy;
use App\Services\SortService;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
  public function setRating(ProductRatingRequest $request)
  {
    $product = Product::where('slug', $request->product_slug)->firstOrFail();
    $value = $request->rating_value;
    $description = $request->description;
    $pros = $request->pros;
    $cons = $request->cons;

    ProductRating::setOrUpdate($product, [
      'value' => $value,
      'description' => $description,
      'pros' => $pros,
      'cons' => $cons
    ]);

    return response([
      'ok' => true,
      'message' => __('general.ratingSet', ['product' => $product->name, 'value' => $value])
    ], 201);
  }

  public function removeRating()
  {
    $product = Product::where('slug', request('product_slug'))->firstOrFail();

    ProductRating::removeRating($product);

    return response([
      'ok' => true,
      'message' => __('general.ratingRemoved', ['product' => $product->name])
    ]);
  }

  public function catalog(ProductFilter $request)
  {
    $sortData = SortService::getSortsFromQuery(Taxonomy::catalogSorts());

    $products = Product::select([
      Product::tableName() . '.id',
      Product::tableName() . '.name',
      Product::tableName() . '.slug',
      Product::tableName() . '.image_id',
      'status_id',
      'brand_id'
    ])
      ->minAndMaxPrice()
      ->filter($request)
      ->activeStatus()
      ->with([
        'image:id,name,extension,path,alt,disk',
        'firstVariation:id,product_id,slug',
        'variations:id,product_id,slug',
        'status:id,value,value_slug',
        'brand:id,value,value_slug',
      ])
      ->withAvg('rating as rating_value', 'value')
      ->withCount('rating as rating_count')
      ->orderBy($sortData['sort'], $sortData['sortOrder'])
      ->paginate(request('per_page') ?? 12);

    $products->transform(function ($prod) {
      $prod->variations = $prod->variations->transform(fn($data) => $data->id);
      return $prod;
    });

    return $products;
  }

  public function productPage($productSlug, $variationSlug)
  {
    $product = Product::select(
      Product::tableName() . '.id',
      Product::tableName() . '.slug',
      Product::tableName() . '.name',
      Product::tableName() . '.description',
      Product::tableName() . '.image_id',
      Product::tableName() . '.status_id',
      Product::tableName() . '.brand_id',
      Product::tableName() . '.category_id',
      Product::tableName() . '.type_id',
    )
      ->where('products.slug', $productSlug)
      ->with([
        'status:id,slug,value,value_slug',
        'brand:id,slug,value,value_slug',
        'category:id,slug,value,value_slug',
        'type:id,slug,value,value_slug',
        'info:id,product_id,name,value',
        'variations:id,product_id,name,slug',
      ])
      ->withAvg('rating as rating_value', 'value')
      ->firstOrFail();

    $variation = ProductVariation::select(
      ProductVariation::tableName() . '.id',
      ProductVariation::tableName() . '.slug',
      ProductVariation::tableName() . '.price',
      ProductVariation::tableName() . '.discount',
      ProductVariation::tableName() . '.name',
      ProductVariation::tableName() . '.quantity',
      DB::raw(Product::priceWithDiscountFormula() . ' as current_price'),
    )
      ->where(ProductVariation::tableName() . '.slug', $variationSlug)
      ->where('product_id', $product->id)
      ->with(['gallery:id,name,extension,path,alt,disk'])
      ->firstOrFail();

    return response([
      'ok' => true,
      'data' => [
        'product' => $product,
        'variation' => $variation,
      ]
    ]);
  }

  public function reviews($productSlug)
  {
    $defaultPerPage = config('constants.product.rating.reviews_per_page');

    $reviews = ProductRating::forProduct($productSlug)
      ->paginate(request('per_page') ?? $defaultPerPage);

    return response([
      'ok' => true,
      'data' => $reviews
    ]);
  }

  public function userReview($productSlug)
  {
    return [
      'ok' => true,
      'data' => ProductRating::where('user_id', auth()->user()->id)
        ->forProduct($productSlug)
        ->first()
    ];
  }
}

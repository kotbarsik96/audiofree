<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\ProductRatingRequest;
use App\Models\Product;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use App\Models\Taxonomy\Taxonomy;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
  public function setRating(ProductRatingRequest $request)
  {
    $product = Product::findOrFail($request->product_id);
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
    $product = Product::findOrFail(request('product_id'));

    ProductRating::removeRating($product);

    return response([
      'ok' => true,
      'message' => __('general.ratingRemoved', ['product' => $product->name])
    ]);
  }

  public function catalog(ProductFilter $request)
  {
    $defaultSort = Taxonomy::sorts();
    $sort = explode('__', $request->request->query('sort', $defaultSort[0]['value'] . '__asc'));
    $sortType = $sort[0];
    $sortDirection = $sort[1];

    $products = Product::select([
      'products.id',
      'products.name',
      'products.image_id',
      'status_id',
      'brand_id'
    ])
      ->minAndMaxPrice()
      ->filter($request)
      ->activeStatus()
      ->with([
        'image:id,name,extension,path,alt,disk',
        'firstVariation:id,product_id',
        'status:id,value,value_slug',
        'brand:id,value,value_slug'
      ])
      ->withAvg('rating as rating_value', 'value')
      ->withCount('rating as rating_count')
      ->orderBy($sortType, $sortDirection)
      ->paginate(request('per_page') ?? 12);

    return $products;
  }

  public function productPage($productId, $variationId)
  {
    $product = Product::select(
      'products.id',
      'products.name',
      'products.description',
      'products.image_id',
      'products.status_id',
      'products.brand_id',
      'products.category_id',
      'products.type_id',
    )
      ->where('products.id', $productId)
      ->with([
        'status:id,slug,value,value_slug',
        'brand:id,slug,value,value_slug',
        'category:id,slug,value,value_slug',
        'type:id,slug,value,value_slug',
        'info:id,product_id,name,value',
        'variations:id,product_id,name',
      ])
      ->withAvg('rating as rating_value', 'value')
      ->firstOrFail();

    $variation = ProductVariation::select(
      'product_variations.id',
      'product_variations.price',
      'product_variations.discount',
      'product_variations.name',
      'product_variations.quantity',
      DB::raw(Product::priceWithDiscountFormula() . ' as current_price'),
    )
      ->where('id', $variationId)
      ->where('product_id', $productId)
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

  public function reviews($productId)
  {
    $defaultPerPage = config('constants.product.rating.reviews_per_page');

    $reviews = ProductRating::forProduct($productId)
      ->paginate(request('per_page') ?? $defaultPerPage);

    return response([
      'ok' => true,
      'data' => $reviews
    ]);
  }

  public function userReview($productId)
  {
    return [
      'ok' => true,
      'data' => ProductRating::
        where('user_id', auth()->user()->id)
        ->forProduct($productId)
        ->first()
    ];
  }
}

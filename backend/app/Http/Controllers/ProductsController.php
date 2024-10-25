<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\ProductRatingRequest;
use App\Models\Product;
use App\Models\Product\ProductRating;
use App\Models\Taxonomy\Taxonomy;

class ProductsController extends Controller
{
  public function setRating(ProductRatingRequest $request)
  {
    $product = Product::findOrFail($request->product_id);
    $value = request('rating_value');

    ProductRating::setOrUpdate($product, $value);

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
      ->withAvg('rating as rating', 'value')
      ->orderBy($sortType, $sortDirection)
      ->paginate(request('per_page') ?? 12);

    return $products;
  }

  public function productPage()
  {
    $product = Product::findOrFail(request('product_id'));
    $variations = $product->variations()
      ->select(['id', 'name', 'image_id', 'price', 'discount', 'quantity'])
      ->get();
    $images = $variations
      ->first(fn($variation) => $variation->id === (int) request('variation_id'))
      ->gallery()
      ->select(['attachments.id', 'name', 'extension', 'sort', 'path', 'alt', 'disk'])
      ->get();

    return response([
      'ok' => true,
      'data' => [
        'product' => $product,
        'variations' => $variations,
        'rating' => ProductRating::avgForProduct($product),
        'images' => $images
      ]
    ]);
  }
}

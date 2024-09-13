<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\ProductInfoRequest;
use App\Http\Requests\Product\ProductRatingRequest;
use App\Http\Requests\Product\ProductRemoveRatingRequest;
use App\Models\Product;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;

class ProductsController extends Controller
{
  public function setRating(ProductRatingRequest $request)
  {
    $product = $request->product;
    $value = $request['rating_value'];

    ProductRating::setOrUpdate($product, $value);

    return response([
      'ok' => true,
      'message' => __('general.ratingSet', ['product' => $product->name, 'value' => $value])
    ], 201);
  }

  public function removeRating(ProductRemoveRatingRequest $request)
  {
    $product = $request->product;

    ProductRating::removeRating($product);

    return response([
      'ok' => true,
      'message' => __('general.ratingRemoved', ['product' => $product->name])
    ]);
  }

  public function catalog(ProductFilter $request)
  {
    $products =  Product::filter($request)->catalog()->onlyInStock()->get();
    foreach ($products as $product) {
      $priceAndDiscount = ProductVariation::minPriceAndDiscount($product->id)
        ->first();
      $product->price = $priceAndDiscount?->price;
      $product->current_min_price = $priceAndDiscount?->current_price;
      $product->discount = $priceAndDiscount?->discount;
    }
    return $products;
  }

  public function productPage()
  {
    $product = Product::forPage(request()->product_id)
      ->first();
    if (!$product)
      abort(404, __('general.notFoundProduct'));

    return response([
      'ok' => true,
      'data' => [
        'product' => $product,
        'variations' => ProductVariation::forProduct($product->id, true),
        'info' => ProductInfo::forProduct($product->id)->get()
      ]
    ]);
  }

  public function productsList(ProductFilter $request)
  {
    if (!Product::allowsStore())
      abort(401, __('abortions.unauthorized'));

    $products = Product::filter($request)
      ->catalog()
      ->get();

    return response([
      'ok' => true,
      'data' => [
        'products' => $products
      ]
    ]);
  }
}

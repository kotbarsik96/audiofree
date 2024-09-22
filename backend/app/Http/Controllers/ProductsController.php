<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\ProductRatingRequest;
use App\Http\Requests\Product\ProductRemoveRatingRequest;
use App\Models\Product;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use App\Models\Taxonomy\TaxonomyValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    $products = Product::select(['id', 'name', 'image_id', 'status_id'])
      ->filter($request)
      ->activeStatus()
      ->with('image:id,name,extension,path,alt,disk')
      ->get()
      ->map(function ($product) {
        $variations = $product->variations();
        $product->min_price = (int) $variations
          ->min(DB::raw('price - (price / 100 * discount)'));
        $product->max_price = (int) $variations
          ->max(DB::raw('price - (price / 100 * discount)'));
        $product->variation = $variations->first()->id;

        return $product;
      });

    return response([
      'ok' => true,
      'data' => $products
    ]);
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
        'rating' => round((float) $product->rating()->avg('value'), 2),
        'images' => $images
      ]
    ]);
  }
}

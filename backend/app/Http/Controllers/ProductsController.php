<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\ProductGalleryRequest;
use App\Http\Requests\Product\ProductInfoRequest;
use App\Http\Requests\Product\ProductRatingRequest;
use App\Http\Requests\Product\ProductRemoveRatingRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Gallery\Gallery;
use App\Models\Image;
use App\Models\Product;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;

class ProductsController extends Controller
{
  public function store(ProductRequest $request)
  {
    $validated = array_merge($request->validated(), [
      'created_by' => auth()->user()->id
    ]);
    $product = Product::create($validated);
    Gallery::create([
      'product_id' => $product->id
    ]);

    return response([
      'ok' => true,
      'data' => $product
    ], 201);
  }

  public function update(ProductRequest $request)
  {
    $validated = $request->validated();

    $product = Product::find($request->product_id);
    if (!$product) {
      abort(400, __('general.notFoundProduct'));
    }
    $product->update(array_merge($validated, [
      'updated_by' => auth()->user()->id,
    ]));

    return response([
      'ok' => true,
      'data' => $product
    ]);
  }

  public function delete()
  {
    $product = Product::find(request()->product_id);
    if (!$product) {
      abort(400, __('general.notFoundProduct'));
    }

    if (request()->remove_images)
      Image::deleteForProduct($product);

    $gallery = Gallery::where('product_id', $product->id);
    if ($gallery) $gallery->delete();

    $name = $product->name;
    $product->delete();

    return [
      'ok' => true,
      'message' => __('general.deletedProduct', ['name' => $name])
    ];
  }

  public function storeVariations()
  {
    $productId = request()->product_id;
    $variations = request()->variations;

    ProductVariation::removeNotInRequest($variations, $productId);
    ProductVariation::storeFromRequest($variations, $productId);

    return response([
      'ok' => true
    ], 200);
  }

  public function uploadGallery(ProductGalleryRequest $request)
  {
    Gallery::uploadForProduct($request->images, $request->product);

    return [
      'ok' => true
    ];
  }

  public function storeInfo(ProductInfoRequest $request)
  {
    $info = $request->validated()['info'];
    ProductInfo::removeNotInRequest($info, $request->product);
    ProductInfo::storeFromRequest($info, $request->product);

    return [
      'ok' => true
    ];
  }

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
    return Product::filter($request)->get();
  }
}

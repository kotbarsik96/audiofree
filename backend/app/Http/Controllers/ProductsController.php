<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\VariationGalleryRequest;
use App\Http\Requests\Product\ProductInfoRequest;
use App\Http\Requests\Product\ProductRatingRequest;
use App\Http\Requests\Product\ProductRemoveRatingRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\ProductVariationRequest;
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

    return response([
      'ok' => true,
      'data' => $product
    ], 201);
  }

  public function update(ProductRequest $request)
  {
    $validated = $request->validated();

    $product = $request->product;
    if (!$product)
      abort(404, __('general.notFoundProduct'));

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
    $product = Product::getOrAbort(request()->product_id);

    $variations = ProductVariation::where('product_id', $product->id)->get();
    foreach ($variations as $variation) {
      $variation->deleteVariation();
    }

    $name = $product->name;
    $product->delete();

    return [
      'ok' => true,
      'message' => __('general.deletedProduct', ['name' => $name])
    ];
  }

  public function storeVariation(ProductVariationRequest $request)
  {
    $validated = $request->validated();
    $product = $request->product;

    $uploadedImage = array_key_exists('image', $validated) ? $validated['image'] : null;
    $image = $uploadedImage ? ProductVariation::uploadImage($product, $uploadedImage) : null;

    $data = array_merge(['quantity' => 0], $validated);
    if ($image)
      $data['image_path'] = $image->path;

    $variation = ProductVariation::createOrUpdate($product, $data);
    $variation->createOrUpdateGallery();

    $images = array_key_exists('images', $validated)
      ? $validated['images'] : null;
    if (is_array($images))
      $variation->uploadGallery($images, $product);

    return response([
      'ok' => true
    ], 200);
  }

  public function deleteVariation(ProductVariationRequest $request)
  {
    $variation = $request->variation;
    if (!$variation)
      abort(404, __('abortions.variationNotFound', ['value' => $request->value]));

    $variation->deleteVariation();

    return response([
      'ok' => true
    ], 200);
  }

  public function uploadGallery(VariationGalleryRequest $request)
  {
    $request->variation->uploadGallery(
      $request->images,
      $request->product
    );

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
    $products =  Product::filter($request)->catalog()->get();
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
      ->catalog(config('constants.product.statuses'))
      ->get();

    return response([
      'ok' => true,
      'data' => [
        'products' => $products
      ]
    ]);
  }
}

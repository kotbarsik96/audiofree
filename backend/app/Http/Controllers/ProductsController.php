<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductGalleryRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Gallery\Gallery;
use App\Models\Image;
use App\Models\Product;
use App\Models\Product\ProductVariation;

class ProductsController extends Controller
{
  public function store(ProductRequest $request)
  {
    $validated = $request->validated();
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
    $product->update($validated);

    return response([
      'ok' => true,
      'data' => $product
    ]);
  }

  public function delete()
  {
    $product = Product::find(request()->product_id);
    if (!$product) {
      abort(400, __('general.notFound'));
    }

    if (request()->remove_images)
      Image::deleteForProduct($product);

    $gallery = Gallery::where('product_id', $product->id);
    if ($gallery) $gallery->delete();

    $product->delete();

    return [
      'ok' => true,
      'message' => __('general.deletedProduct')
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
}

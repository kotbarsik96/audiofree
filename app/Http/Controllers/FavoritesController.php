<?php

namespace App\Http\Controllers;

use App\DTO\Sort\SortDTOCollection;
use App\Enums\SortEnum;
use App\Filters\ProductFilter;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use App\Models\Taxonomy\TaxonomyValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Attachment\Models\Attachment;

class FavoritesController extends Controller
{
  public function store(Request $request)
  {
    $variation = ProductVariation::itemOrFail($request->input('variation_id'));

    Favorite::firstOrCreate([
      'user_id' => auth()->user()->id,
      'variation_id' => $variation->id,
    ]);
    $product = Product::find($variation->product_id);

    return response([
      'ok' => true,
      'message' => __(
        'general.addedToFavorites',
        [
          'product' => $product->name,
          'variation' => $variation->name
        ]
      ),
    ]);
  }

  public function get(ProductFilter $request)
  {
    $sortData = SortDTOCollection::getSortsFromRequest(SortEnum::FAVORITES);

    $favoriteFields = ['favorites.id', 'favorites.created_at'];
    $productFields = [
      Product::tableName() . '.id as product_id',
      Product::tableName() . '.name as product_name',
      Product::tableName() . '.slug as product_slug',
      Product::tableName() . '.status_id as product_status_id',
    ];
    $variationFields = [
      ProductVariation::tableName() . '.id as variation_id',
      ProductVariation::tableName() . '.image_id as image_id',
      ProductVariation::tableName() . '.name as variation_name',
      ProductVariation::tableName() . '.slug as variation_slug',
      ProductVariation::tableName() . '.price',
      ProductVariation::tableName() . '.discount',
      ProductVariation::tableName() . '.quantity',
      DB::raw(ProductVariation::currentPriceSelectFormula()),
      DB::raw('concat(' . Product::tableName() . '.name, " ",' . ProductVariation::tableName() . '.name) as full_name')
    ];
    $ratingFields = [
      DB::raw('avg(' . ProductRating::tableName() . '.value) as rating_value'),
      DB::raw('count(' . ProductRating::tableName() . '.value) as rating_count'),
    ];

    $favorites = Favorite::select(array_merge(
      $favoriteFields,
      $productFields,
      $variationFields,
      $ratingFields
    ))
      ->where('favorites.user_id', auth()->user()->id)
      ->join(
        ProductVariation::tableName(),
        ProductVariation::tableName() . '.id',
        '=',
        'variation_id'
      )
      ->join(
        Product::tableName(),
        Product::tableName() . '.id',
        '=',
        ProductVariation::tableName() . '.product_id'
      )
      ->leftJoin(
        ProductRating::tableName(),
        ProductRating::tableName() . '.product_id',
        '=',
        Product::tableName() . '.id'
      )
      ->filter($request)
      ->orderBy($sortData['sort'], $sortData['sortOrder'])
      ->groupBy('favorites.id')
      ->paginate(request('per_page') ?? 12);

    $favorites->transform(function ($item) {
      $item->image = Attachment::find($item->image_id)->url();
      $item->status = TaxonomyValue::find($item->product_status_id);
      if ($item->status) $item->status = $item->status->value_slug;
      return $item;
    });

    return response([
      'ok' => true,
      'data' => $favorites
    ]);
  }

  public function delete(Request $request)
  {
    $result = null;

    if ($request->input('variation_id')) {
      $variation = ProductVariation::itemOrFail($request->input('variation_id'));
      $result = $this->deleteByVariation($variation);
    } else if ($request->input('product_id')) {
      $product = Product::itemOrFail($request->input('product_id'));
      $result = $this->deleteByProduct($product);
    } else {
      return response([
        'message' => __('abortions.productNotFound')
      ], 400);
    }

    return $result;
  }

  public function deleteByVariation(ProductVariation $variation)
  {
    Favorite::firstOrNew([
      'user_id' => auth()->user()->id,
      'variation_id' => $variation->id
    ])->delete();

    $product = Product::find($variation->product_id);

    return response([
      'ok' => true,
      'message' => __('general.removedFromFavorites', [
        'product' => $product->name,
        'variation' => $variation->name
      ])
    ]);
  }

  public function deleteByProduct(Product $product)
  {
    $variations = ProductVariation::select('id')->where('product_id', $product->id)->get();
    $variations->each(
      fn($var) =>
      Favorite::where('variation_id', $var->id)
        ->where('user_id', auth()->user()->id)
        ->delete()
    );

    return response([
      'ok' => true,
      'message' => __('general.removedProductFromFavorites', [
        'product' => $product->name
      ])
    ]);
  }
}

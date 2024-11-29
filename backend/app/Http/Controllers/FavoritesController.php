<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use App\Models\Taxonomy\Taxonomy;
use App\Services\SortService;
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

  public function get()
  {
    $sortData = SortService::getSortsFromQuery(Taxonomy::favoritesSorts());

    $favoriteFields = ['favorites.id', 'favorites.created_at'];
    $productFields = [
      Product::tableName() . '.id as product_id',
      Product::tableName() . '.name as product_name'
    ];
    $variationFields = [
      ProductVariation::tableName() . '.id as variation_id',
      ProductVariation::tableName() . '.image_id as image_id',
      ProductVariation::tableName() . '.name as variation_name',
      ProductVariation::tableName() . '.price',
      ProductVariation::tableName() . '.discount',
      ProductVariation::tableName() . '.quantity',
      DB::raw(ProductVariation::currentPriceSelectFormula())
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
      ->orderBy($sortData['sort'], $sortData['sortOrder'])
      ->groupBy('favorites.id')
      ->paginate(request('per_page') ?? 12);

    $favorites->transform(function ($item) {
      $item->image = Attachment::find($item->image_id)->url();
      return $item;
    });

    return response([
      'ok' => true,
      'data' => $favorites
    ]);
  }

  public function delete(Request $request)
  {
    $variation = ProductVariation::itemOrFail($request->input('variation_id'));

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
}

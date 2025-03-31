<?php

namespace App\Http\Controllers;

use App\DTO\Sort\SortDTOCollection;
use App\Enums\SortEnum;
use App\Models\Taxonomy\Taxonomy;

class TaxonomiesController extends Controller
{
  public function catalogFilters()
  {
    $filters = Taxonomy::filters()->select(['id', 'name', 'slug'])
      ->with('values:id,slug,value,value_slug')
      ->get();

    $filters = Taxonomy::mapFilters($filters);

    return response([
      'ok' => true,
      'data' => $filters,
    ], 200);
  }

  public function catalogSorts()
  {
    return response([
      'ok' => true,
      'data' => SortDTOCollection::getDTO(SortEnum::CATALOG)->sorts
    ]);
  }

  public function favoritesSorts()
  {
    return response([
      'ok' => true,
      'data' => SortDTOCollection::getDTO(SortEnum::FAVORITES)->sorts
    ]);
  }

  public function orderSorts()
  {
    return response([
      'ok' => true,
      'data' => SortDTOCollection::getDTO(SortEnum::ORDERS)->sorts
    ]);
  }
}

<?php

namespace App\Http\Controllers;

use App\Filters\QueryFilter;
use App\Models\Product;
use App\Models\Taxonomy\Taxonomy;
use Illuminate\Support\Facades\DB;

class TaxonomiesController extends Controller
{
  public function filters(QueryFilter $request)
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
}

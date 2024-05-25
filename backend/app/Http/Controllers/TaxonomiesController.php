<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Taxonomy\Taxonomy;
use Illuminate\Http\Request;

class TaxonomiesController extends Controller
{
  public function getTypesForCatalog()
  {
    $taxonomies = Taxonomy::forCatalog()
      ->get()
      ->groupBy('type')
      ->map(fn($item, $type) => ['type' => $type, 'values' => $item->pluck('name')])
      ->toArray();

    return response([
      'ok' => true,
      'data' => [
        'taxonomies' => array_values($taxonomies)
      ]
    ], 200);
  }
}

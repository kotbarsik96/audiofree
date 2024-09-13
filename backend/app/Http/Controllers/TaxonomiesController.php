<?php

namespace App\Http\Controllers;

use App\Models\Taxonomy\TaxonomyValue;

class TaxonomiesController extends Controller
{
  public function getTypesForCatalog()
  {
    $taxonomies = TaxonomyValue::forCatalog()
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

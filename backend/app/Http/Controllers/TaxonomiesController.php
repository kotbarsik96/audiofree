<?php

namespace App\Http\Controllers;

use App\Models\Taxonomy\Taxonomy;

class TaxonomiesController extends Controller
{
  public function catalog()
  {
    $taxonomies = Taxonomy::catalog()->select(['id', 'name', 'slug'])
      ->with('values:id,slug,value,value_slug')
      ->get();

    return response([
      'ok' => true,
      'data' => $taxonomies
    ], 200);
  }
}

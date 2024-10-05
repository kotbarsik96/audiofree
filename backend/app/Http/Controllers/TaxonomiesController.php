<?php

namespace App\Http\Controllers;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyValue;

class TaxonomiesController extends Controller
{
  public function catalog()
  {
    $taxonomies = Taxonomy::catalog()->select(['id', 'name', 'slug'])->get();

    return response([
      'ok' => true,
      'data' => $taxonomies
    ], 200);
  }
}

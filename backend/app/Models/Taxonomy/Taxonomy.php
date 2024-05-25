<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Taxonomy extends Model
{
  use HasFactory;

  protected $fillable = [
    'type',
    'name'
  ];

  protected $table = 'taxonomies';

  public function scopeForCatalog(Builder $query)
  {
    $catalogTaxonomies = config('constants.product.catalog_taxonomies_types');
    $query->select(['type', 'name'])
      ->whereIn('type', $catalogTaxonomies);
  }
}

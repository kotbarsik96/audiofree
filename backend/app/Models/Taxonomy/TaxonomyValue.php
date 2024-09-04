<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TaxonomyValue extends Model
{
  use HasFactory;

  protected $fillable = [
    'taxonomy_name',
    'value'
  ];

  protected $table = 'taxonomy_values';

  public function scopeForCatalog(Builder $query)
  {
    $catalogTaxonomies = config('constants.product.catalog_taxonomies');
    $query->select(['taxonomy_name', 'value'])
      ->whereIn('taxonomy_name', $catalogTaxonomies);
  }
}

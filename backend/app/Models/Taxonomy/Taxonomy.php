<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
  use HasFactory;

  protected $fillable = [
    'slug',
    'name',
    'group'
  ];

  protected $table = 'taxonomies';

  public function values()
  {
    return $this->hasMany(TaxonomyValue::class, 'slug', 'slug');
  }

  public function scopeCatalog(Builder $query)
  {
    return $query->whereIn('slug', [
      'brand',
      'category',
      'type',
      'product_status'
    ]);
  }
}

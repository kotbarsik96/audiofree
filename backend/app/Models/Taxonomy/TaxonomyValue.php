<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TaxonomyValue extends Model
{
  use HasFactory;

  protected $fillable = [
    'slug',
    'value',
    'value_slug'
  ];

  protected $table = 'taxonomy_values';
}

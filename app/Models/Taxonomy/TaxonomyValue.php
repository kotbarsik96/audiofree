<?php

namespace App\Models\Taxonomy;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxonomyValue extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'slug',
    'value',
    'value_slug'
  ];

  protected $table = 'taxonomy_values';
}

<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxonomyType extends Model
{
  use HasFactory;

  protected $fillable = [
    'type'
  ];

  protected $table = 'taxonomies_types';
}

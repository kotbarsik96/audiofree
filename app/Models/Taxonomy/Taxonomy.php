<?php

namespace App\Models\Taxonomy;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Taxonomy extends BaseModel
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
}

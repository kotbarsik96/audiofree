<?php

namespace App\Models\Taxonomy;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;

class TaxonomyValue extends BaseModel
{
  use HasFactory, Attachable;

  protected $fillable = [
    'slug',
    'value',
    'value_slug',
    'image_id'
  ];

  protected $table = 'taxonomy_values';

  public function image()
  {
    return $this->hasOne(Attachment::class, 'id', 'image_id')->withDefault();
  }
}

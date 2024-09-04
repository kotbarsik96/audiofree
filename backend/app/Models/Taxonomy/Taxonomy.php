<?php

namespace App\Models\Taxonomy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'group'
  ];

  protected $table = 'taxonomies';
}

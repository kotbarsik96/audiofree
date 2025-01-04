<?php

namespace App\Models\Gallery;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'name',
    'variation_id'
  ];
}

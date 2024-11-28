<?php

namespace App\Models;

use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Favorite extends BaseModel
{
  use HasFactory;

  protected $table = 'favorites';

  protected $fillable = [
    'user_id',
    'variation_id'
  ];

  protected $casts = [
    'current_price' => 'integer'
  ];

  public function variation()
  {
    return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
  }
}

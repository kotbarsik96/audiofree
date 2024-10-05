<?php

namespace App\Models;

use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Favorite extends Model
{
  use HasFactory;

  protected $table = 'favorites';

  protected $fillable = [
    'user_id',
    'variation_id'
  ];

  protected $casts = [
    'current_price' => 'integer',
  ];

  public function variation()
  {
    return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
  }
}

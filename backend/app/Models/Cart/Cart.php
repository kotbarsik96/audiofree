<?php

namespace App\Models\Cart;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
  use HasFactory;

  protected $table = 'cart';

  protected $fillable = [
    'user_id',
    'variation_id',
    'is_oneclick',
    'quantity'
  ];

  protected $casts = [
    'current_price' => 'integer'
  ];
}

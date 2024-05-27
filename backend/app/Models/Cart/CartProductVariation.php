<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProductVariation extends Model
{
  use HasFactory;

  protected $table = 'cart_product_variation';
}

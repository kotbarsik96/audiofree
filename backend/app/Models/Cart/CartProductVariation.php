<?php

namespace App\Models\Cart;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartProductVariation extends BaseModel
{
  use HasFactory;

  protected $table = 'cart_product_variation';
}

<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'user_id',
    'value'
  ];
}

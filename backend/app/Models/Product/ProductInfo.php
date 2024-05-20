<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'name',
    'value'
  ];
}

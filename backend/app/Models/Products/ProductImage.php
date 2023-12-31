<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    public $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_id',
        'tag'
    ];
}

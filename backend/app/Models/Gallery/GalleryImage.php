<?php

namespace App\Models\Gallery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
  use HasFactory;

  protected $table = 'galleries_images';

  protected $fillable = [
    'gallery_id',
    'image_path',
    'order'
  ];
}

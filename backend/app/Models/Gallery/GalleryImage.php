<?php

namespace App\Models\Gallery;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GalleryImage extends BaseModel
{
  use HasFactory;

  protected $table = 'galleries_images';

  protected $fillable = [
    'gallery_id',
    'image_path',
    'order'
  ];
}

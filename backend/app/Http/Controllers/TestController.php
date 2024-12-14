<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Storage;

class TestController extends Controller
{
  public function uploadImage(Request $request)
  {
    $parseurl = parse_url('https://8b3126d5bba2-shrewd-ian.s3.ru1.storage.beget.cloud/audiofree/products/f8e597730f38e9815edc256acd28dd193a6fa686.webp');

    return [
      'parse_url' => $parseurl,
      'pathinfo' => pathinfo($parseurl['path']),
    ];
  }
}

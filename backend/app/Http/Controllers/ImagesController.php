<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\ImageDeleteRequest;
use App\Http\Requests\Image\ImageStoreRequest;
use App\Models\Image;

class ImagesController extends Controller
{
  public function upload(ImageStoreRequest $request)
  {
    Image::upload($request->image);

    return [
      'ok' => true
    ];
  }

  public function delete(ImageDeleteRequest $request)
  {
    Image::deleteImage($request->image);

    return [
      'ok' => true
    ];
  }
}

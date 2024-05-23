<?php

namespace App\Validations;

class ImageValidation
{
  public static function image()
  {
    return 'required|image|mimes:png,jpg,jpeg|max:2048';
  }

  public static function imagePath()
  {
    return 'exists:images,path';
  }
}

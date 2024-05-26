<?php

namespace App\Validations;

class ImageValidation
{
  public static function image($required = false)
  {
    $rules = ['image', 'mimes:png,jpg,jpeg', 'max:2048'];
    if($required)
      array_push($rules, 'required');
    return $rules;
  }

  public static function imagePath()
  {
    return 'exists:images,path';
  }

  public static function messages()
  {
    return [
      'image' => __('validation.image'),
      'images.*' => __('validation.images')
    ];
  }
}

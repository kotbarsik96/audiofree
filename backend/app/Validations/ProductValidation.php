<?php

namespace App\Validations;

class ProductValidation
{
  public static function name($required = false, $ignoreId = null)
  {
    $array = ['string', 'min:3', 'unique:products,name,' . $ignoreId];
    if ($required) array_push($array, 'required');
    return $array;
  }

  public static function price($required = false)
  {
    $array = ['numeric'];
    if ($required) array_push($array, 'required');
    return $array;
  }

  public static function discount()
  {
    return "numeric|min:0|max:100";
  }

  public static function quantity()
  {
    return "numeric";
  }

  public static function taxonomy($required = false)
  {
    $array = ['string', 'exists:taxonomies,name'];
    if ($required) array_push($array, 'required');
    return $array;
  }

  public static function ratingValue()
  {
    $max = config('constants.product.rating.max');
    return ['numeric', 'min:0', 'max:' . $max];
  }

  public static function imagePath()
  {
    return 'exists:images,path';
  }

  public static function description()
  {
    $max = config('constants.product.description.maxlength');

    return ['max:' . $max];
  }

  public static function messages()
  {
    return [
      'name.required' => __('validation.name.required'),
      'name.min' => __('validation.name.min'),
      'name.unique' => __('validation.productName.unique'),
      'price.numeric' => __('validation.price.numeric'),
      'discount' => __('validation.discount'),
      'price.min' => __('validation.price.min'),
      'quantity' => __('validation.quantity'),
      'status.exists' => __('validation.status.exists'),
      'type.exists' => __('validation.type.exists'),
      'category.exists' => __('validation.category.exists'),
      'brand.exists' => __('validation.brand.exists'),
      'required' => __('validation.required'),
      'image_path' => __('validation.image_path'),
      'rating_value' => __('validation.rating_value'),
      'description.max' => __('validation.product.description.max')
    ];
  }
}

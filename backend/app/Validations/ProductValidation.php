<?php

namespace App\Validations;

class ProductValidation
{
  public static function name($required = false)
  {
    $array = ['string', 'min:3'];
    if ($required) array_push($array, 'required');
    return $array;
  }

  public static function price($required = false)
  {
    $array = ['numeric'];
    if ($required) array_push($array, 'required');
    return $array;
  }

  public static function discountPrice()
  {
    return "lt:price|numeric|min:1";
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
    return ['numeric', 'min:1', 'max:' . $max];
  }

  public static function messages()
  {
    return [
      'name.required' => __('validation.name.required'),
      'name.min' => __('validation.name.min'),
      'price.numeric' => __('validation.price.numeric'),
      'discount_price.numeric' => __('validation.price.numeric'),
      'price.min' => __('validation.price.min'),
      'discount_price.min' => __('validation.price.min'),
      'quantity' => __('validation.quantity'),
      'discount_price.lt' => __('validation.discount_price.lt'),
      'status.exists' => __('validation.status.exists'),
      'type.exists' => __('validation.type.exists'),
      'category.exists' => __('validation.category.exists'),
      'required' => __('validation.required'),
      'image_path' => __('validation.image_path'),
      'rating_value' => __('validation.rating_value')
    ];
  }
}

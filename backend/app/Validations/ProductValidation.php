<?php

namespace App\Validations;

class ProductValidation
{
  public static function name()
  {
    return "string|required|min:3";
  }
  
  public static function price()
  {
    return "numeric|required";
  }

  public static function discountPrice()
  {
    return "lt:price|numeric|min:1";
  }

  public static function quantity()
  {
    return "numeric";
  }

  public static function taxonomy()
  {
    return 'required|string|exists:taxonomies,name';
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
      'required' => __('validation.required')
    ];
  }
}

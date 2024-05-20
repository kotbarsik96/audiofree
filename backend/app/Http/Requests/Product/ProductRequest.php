<?php

namespace App\Http\Requests\Product;

use App\Validations\ProductValidation;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class ProductRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return Product::allowsStore();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => ProductValidation::name(),
      'price' => ProductValidation::price(),
      'discount_price' => ProductValidation::discountPrice(),
      'quantity' => ProductValidation::quantity(),
      'status' => ProductValidation::taxonomy(),
      'type' => ProductValidation::taxonomy(),
      'status' => ProductValidation::taxonomy(),
      'category' => ProductValidation::taxonomy(),
    ];
  }

  public function messages()
  {
    return ProductValidation::messages();
  }
}

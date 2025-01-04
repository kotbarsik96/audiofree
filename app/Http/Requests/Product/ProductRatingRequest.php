<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Validations\ProductValidation;
use Illuminate\Foundation\Http\FormRequest;


class ProductRatingRequest extends FormRequest
{
  public Product $product;

  public function authorize(): bool
  {
    return !!auth()->user();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'rating_value' => ProductValidation::ratingValue(),
      'description' => ProductValidation::ratingDescription(),
      'pros' => ProductValidation::ratingDescription(),
      'cons' => ProductValidation::ratingDescription(),
    ];
  }

  public function messages()
  {
    return ProductValidation::messages();
  }
}

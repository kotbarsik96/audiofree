<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Validations\ProductValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class ProductRatingRequest extends FormRequest
{
  public Product $product;

  public function authorize(): bool
  {
    $this->product = Product::getOrAbort(request()->product_id);
    return Gate::allows('set-rating');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'rating_value' => ProductValidation::ratingValue()
    ];
  }

  public function messages()
  {
    return ProductValidation::messages();
  }
}

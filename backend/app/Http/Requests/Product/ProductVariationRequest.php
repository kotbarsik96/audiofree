<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Validations\ImageValidation;
use App\Validations\ProductValidation;
use Illuminate\Validation\Rule;

class ProductVariationRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $ignoreId = request('id');

    return [
      'price' => ProductValidation::price(),
      'discount' => ProductValidation::discount(),
      'quantity' => ProductValidation::quantity(),
      'value' => [
        Rule::unique('product_variation_values', 'value')
          ->where(fn($query) => $query->where('product_id', $this->product->id))
          ->ignore($ignoreId),
        'min:2'
      ],
    ];
  }

  public function messages()
  {
    return array_merge(ProductValidation::messages(), ImageValidation::messages());
  }
}

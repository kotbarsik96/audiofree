<?php

namespace App\Http\Requests\Product;

use App\Services\InputModifier;
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

  public function prepareForValidation(): void
  {
    $this->merge([
      'product_id' => $this->product_id,
      'price' => InputModifier::stringToNumber($this->price),
      'discount' => InputModifier::stringToNumber($this->discount),
    ]);
  }

  public function rules(): array
  {
    $ignoreId = request('id');

    return [
      'product_id' => ProductValidation::productId(),
      'price' => ProductValidation::price(),
      'discount' => ProductValidation::discount(),
      'quantity' => ProductValidation::quantity(),
      'name' => [
        Rule::unique('product_variations', 'name')
          ->where(fn($query) => $query->where('product_id', $ignoreId))
          ->ignore($ignoreId),
        'min:2'
      ],
      'image_id' => ProductValidation::imageId()
    ];
  }

  public function messages()
  {
    return array_merge(ProductValidation::messages(), ImageValidation::messages());
  }
}

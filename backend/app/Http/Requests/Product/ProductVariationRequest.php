<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Validations\ImageValidation;
use App\Validations\ProductValidation;
use Illuminate\Validation\Rule;

class ProductVariationRequest extends FormRequest
{
  public Product $product;
  public ProductVariation | null $variation;

  public function authorize(): bool
  {
    $this->product = Product::getOrAbort(request()->product_id);
    $this->variation = ProductVariation::getByValue($this->product->id, request()->value);
    return Product::allowsStore($this->product);
  }

  public function rules(): array
  {
    return [
      'price' => ProductValidation::price(),
      'discount' => ProductValidation::discount(),
      'image_path' => ImageValidation::imagePath(),
      'quantity' => ProductValidation::quantity(),
      'value' => [
        Rule::unique('product_variation_values', 'value')
          ->where(fn ($query) => $query->where('product_id', $this->product->id)),
        'min:2'
      ],
      'image' => ImageValidation::image(),
      'images.*' => ImageValidation::image()
    ];
  }

  public function messages()
  {
    return ProductValidation::messages();
  }
}

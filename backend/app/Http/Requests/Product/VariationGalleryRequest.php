<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Validations\ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class VariationGalleryRequest extends FormRequest
{
  public Product $product;
  public ProductVariation $variation;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    $this->product = Product::getOrAbort(request()->product_id);
    $this->variation = ProductVariation::getByNameOrAbort(
      $this->product->id,
      request()->variation_name
    );
    return Product::allowsStore($this->product);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'images.*' => ImageValidation::image()
    ];
  }

  public function messages()
  {
    return [
      'images' => __('validation.image_path')
    ];
  }
}

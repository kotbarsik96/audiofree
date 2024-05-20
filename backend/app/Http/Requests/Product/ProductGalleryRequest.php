<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Validations\ImageValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductGalleryRequest extends FormRequest
{
  public Product $product;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    $this->product = Product::find(request()->product_id);
    if (!$this->product) {
      abort(400, __('abortions.productNotFound'));
    }

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
}

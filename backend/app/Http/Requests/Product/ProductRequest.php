<?php

namespace App\Http\Requests\Product;

use App\Validations\ProductValidation;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;
use App\Validations\ImageValidation;

class ProductRequest extends FormRequest
{
  public Product | null $product;

  public function authorize(): bool
  {
    $this->product = Product::find(request()->product_id);
    return Product::allowsStore($this->product);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $hasProduct = !request()->product_id;

    return [
      'name' => ProductValidation::name($hasProduct),
      'price' => ProductValidation::price($hasProduct),
      'discount_price' => ProductValidation::discountPrice(),
      'quantity' => ProductValidation::quantity(),
      'status' => ProductValidation::taxonomy($hasProduct),
      'type' => ProductValidation::taxonomy($hasProduct),
      'status' => ProductValidation::taxonomy($hasProduct),
      'category' => ProductValidation::taxonomy($hasProduct),
      'image_path' => ImageValidation::imagePath()
    ];
  }

  public function messages()
  {
    return ProductValidation::messages();
  }
}

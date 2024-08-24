<?php

namespace App\Http\Requests\Product;

use App\Validations\ProductValidation;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;
use App\Validations\ImageValidation;

class ProductRequest extends FormRequest
{
  public Product | null $product = null;

  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $isUpdate = !request()->product_id;
    $taxonomyValidation = ProductValidation::taxonomy($isUpdate);
    $ignoreId = $this->product ? $this->product->id : null;

    return [
      'name' => ProductValidation::name($isUpdate, $ignoreId),
      'status' => $taxonomyValidation,
      'type' => $taxonomyValidation,
      'brand' => $taxonomyValidation,
      'category' => $taxonomyValidation
    ];
  }

  public function messages()
  {
    return ProductValidation::messages();
  }
}

<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductInfoRequest extends FormRequest
{
  public Product $product;

  public function authorize(): bool
  {
    $this->product = Product::getOrAbort(request()->product_id);
    return Product::allowsStore($this->product);
  }

  public function rules(): array
  {
    return [
      'info.*.name' => 'required|min:2',
      'info.*.value' => 'required|min:2',
      'product_id' => 'exists:products,id'
    ];
  }
}

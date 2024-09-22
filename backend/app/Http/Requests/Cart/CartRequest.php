<?php

namespace App\Http\Requests\Cart;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
  public function authorize(): bool
  {
    return !!auth()->user();
  }


  public function rules(): array
  {
    return [
      'variation_id' => 'exists:product_variations,id'
    ];
  }
}

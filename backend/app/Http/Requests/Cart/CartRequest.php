<?php

namespace App\Http\Requests\Cart;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
  public Product $product;
  public ProductVariation $variation;
  public bool $is_oneclick;

  public function __construct()
  {
    $this->product = Product::getOrAbort(request()->product_id);

    if ($this->product->status !== 'active')
      abort(403, __('abortions.productIsInactive'));

    $this->variation = ProductVariation::getByValueOrAbort(
      $this->product->id,
      request()->variation
    );

    if (!request()->is_oneclick)
      $this->is_oneclick = false;
    else
      $this->is_oneclick = true;
  }

  public function authorize(): bool
  {
    return Role::isUser(auth()->user());
  }


  public function rules(): array
  {
    return [];
  }
}

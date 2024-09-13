<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Models\Product\ProductRating;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProductRemoveRatingRequest extends FormRequest
{
  public Product $product;
  public ProductRating | null $ratingData;

  public function authorize(): bool
  {
    $user = auth()->user();
    if (!$user) return false;

    $this->product = Product::getOrAbort(request()->product_id);
    $this->ratingData = ProductRating::getOrAbort($this->product->id, $user->id);
    return Gate::allows('remove-rating', $this->ratingData);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      //
    ];
  }
}

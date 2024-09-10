<?php

namespace App\Http\Requests\Product;

use App\Validations\ProductValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function prepareForValidation()
  {
    $this->merge([
      'info' => collect($this->infoName)
        ->combine($this->infoValue)
        ->unique()
        ->map(fn($value, $name) => ['name' => $name, 'value' => $value])
        ->toArray()
    ]);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = request()->id;
    $isUpdate = !!$id;
    $taxonomyValidation = ProductValidation::taxonomy($isUpdate);

    return [
      'name' => ProductValidation::name($isUpdate, $id),
      'status_id' => $taxonomyValidation,
      'type_id' => $taxonomyValidation,
      'brand_id' => $taxonomyValidation,
      'category_id' => $taxonomyValidation,
      'description' => ProductValidation::description(),
      'info' => 'array'
    ];
  }

  public function messages()
  {
    return ProductValidation::messages();
  }
}

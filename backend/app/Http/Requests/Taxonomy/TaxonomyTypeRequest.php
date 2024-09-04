<?php

namespace App\Http\Requests\Taxonomy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxonomyTypeRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
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
    $uniqueRule = Rule::unique('taxonomies_types')->ignore(request()->input('id'));

    return [
      'group' => ['nullable', 'string'],
      'type' => [$uniqueRule, 'required', 'string']
    ];
  }

  public function messages()
  {
    return [
      'group' => __('validation.taxonomy.group'),
      'type' => __('validation.taxomy.type')
    ];
  }
}

<?php

namespace App\Http\Requests\Taxonomy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxonomyRequest extends FormRequest
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
    $uniqueRule = Rule::unique('taxonomies')->ignore(request()->input('id'));

    return [
      'group' => ['nullable', 'string'],
      'name' => [$uniqueRule, 'required', 'string']
    ];
  }

  public function messages()
  {
    return [
      'group' => __('validation.taxonomy.group'),
      'name' => __('validation.taxonomy.name')
    ];
  }
}

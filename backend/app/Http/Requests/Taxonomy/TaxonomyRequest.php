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
      'name' => [$uniqueRule, 'required', 'string'],
      'slug' => [$uniqueRule, 'required', 'string'],
      'group' => ['nullable', 'string'],
    ];
  }

  public function messages()
  {
    return [
      'name' => __('validation.taxonomy.name'),
      'slug' => __('validation.taxonomy.slug'),
      'group' => __('validation.taxonomy.group'),
    ];
  }
}

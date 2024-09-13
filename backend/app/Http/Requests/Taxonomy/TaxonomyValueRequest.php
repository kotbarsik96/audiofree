<?php

namespace App\Http\Requests\Taxonomy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxonomyValueRequest extends FormRequest
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
    $ignoreId = request()->get('id');

    $uniqueRuleValue = Rule::unique('taxonomy_values', 'value')
      ->ignore($ignoreId)
      ->where(fn($query) => $query->where('slug', request()->slug));
    $uniqueRuleValueSlug = Rule::unique('taxonomy_values', 'value_slug')
      ->ignore($ignoreId)
      ->where(fn($query) => $query->where('slug', request()->slug));

    return [
      'slug' => 'required|exists:taxonomies,slug',
      'value' => ['required', $uniqueRuleValue],
      'value_slug' => ['required', $uniqueRuleValueSlug]
    ];
  }

  public function messages()
  {
    return [
      'slug' => __('validation.taxonomyValue.slug'),
      'value' => __('validation.taxonomyValue.value'),
      'value_slug' => __('validation.taxonomyValue.value_slug'),
    ];
  }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'title' => $this->title ? htmlspecialchars($this->title) : null,
            'description' => $this->description ? htmlspecialchars($this->description) : null
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slug' => 'string|min:1',
            'title' => 'string|nullable',
            'description' => 'string|nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'string' => __('validation.string'),
            'required' => __('validation.required'),
            'min' => __('validation.min.string')
        ];
    }
}

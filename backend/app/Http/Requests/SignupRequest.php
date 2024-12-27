<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Validations\AuthValidation;


class SignupRequest extends FormRequest
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
    return [
      'email' => AuthValidation::emailRequiredWithout(),
      // 'telegram' => AuthValidation::telegramRequiredWithout(),
      'name' => AuthValidation::name(),
      'password' => AuthValidation::passwordNullable(),
    ];
  }

  public function messages()
  {
    return AuthValidation::messages();
  }
}

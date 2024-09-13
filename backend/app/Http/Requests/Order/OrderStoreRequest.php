<?php

namespace App\Http\Requests\Order;

use App\Models\Role;
use App\Validations\AuthValidation;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
  public bool $is_oneclick;

  public function __construct()
  {
    if (request()->is_oneclick)
      $this->is_oneclick = true;
    else
      $this->is_oneclick = false;
  }

  public function authorize(): bool
  {
    return Role::isUser(auth()->user());
  }

  public function rules(): array
  {
    return [
      'address' => 'required|min:15',
      'comment' => 'string',
      'name' => AuthValidation::name(),
      'email' => AuthValidation::email(),
      'phone_number' => AuthValidation::phoneNumber(),
    ];
  }

  public function messages()
  {
    return array_merge(AuthValidation::messages(), [
      'address.required' => __('validation.address.required'),
      'address.min' => __('validation.address.min'),
    ]);
  }
}

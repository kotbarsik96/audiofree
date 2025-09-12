<?php

namespace App\Http\Requests\SupportChat;

use App\Rules\CannotMessageSelf;
use Illuminate\Foundation\Http\FormRequest;

class SupporterRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()->hasAccess('support.supporter');
  }

  public function rules(): array
  {
    return [
      'chat_user_id' => ['required', new CannotMessageSelf]
    ];
  }

  public function messages()
  {
    return [
      'chat_user_id.required' => __('validation.support.chatUserIdRequired'),
    ];
  }
}

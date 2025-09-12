<?php

namespace App\Http\Requests\SupportChat;

use App\Rules\CannotMessageSelf;
use Illuminate\Foundation\Http\FormRequest;

class SupporterNewMessageRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()->hasAccess('support.supporter');
  }

  public function rules(): array
  {
    return [
      'message' => 'required',
      'chat_user_id' => ['required', new CannotMessageSelf]
    ];
  }

  public function messages()
  {
    return [
      'message.required' => __('validation.support.messageRequired'),
      'chat_user_id.required' => __('validation.support.chatUserIdRequired'),
    ];
  }
}

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
      'chat_id' => ['required', new CannotMessageSelf, 'exists:support_chats,id']
    ];
  }

  public function messages()
  {
    return [
      'chat_id.required' => __('validation.support.chatIdRequired'),
      'chat_id.exists' => __('validation.chat.notExists')
    ];
  }
}

<?php

namespace App\Http\Requests\SupportChat;

use Illuminate\Foundation\Http\FormRequest;

class SupportChatRequest extends FormRequest
{
  public function authorize(): bool
  {
    return !$this->get('chat_id') || auth()->user()->hasAccess('support.supporter');
  }

  public function rules(): array
  {
    return [

    ];
  }
}

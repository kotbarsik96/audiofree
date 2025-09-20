<?php

namespace App\Http\Requests\SupportChat;

use Illuminate\Foundation\Http\FormRequest;

class SupportChatsListRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth()->user()->hasAccess('support.supporter');
  }
}

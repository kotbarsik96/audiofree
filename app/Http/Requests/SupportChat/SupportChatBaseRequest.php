<?php

namespace App\Http\Requests\SupportChat;

use Illuminate\Foundation\Http\FormRequest;

class SupportChatBaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->has('chat_id') ? $this->authorizeStaff() : true;
    }

    public function authorizeStaff()
    {
        return auth()->user()->hasAccess('platform.systems.support');
    }

    public function rules(): array
    {
        return [];
    }

    public function messages()
    {
        return [
            'chat_id.exists' => 'Chat not found'
        ];
    }
}

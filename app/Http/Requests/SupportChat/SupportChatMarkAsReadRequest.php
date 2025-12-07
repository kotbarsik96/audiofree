<?php

namespace App\Http\Requests\SupportChat;

use App\Http\Requests\SupportChat\SupportChatBaseRequest;

class SupportChatMarkAsReadRequest extends SupportChatBaseRequest
{
    public function rules(): array
    {
        return [
            'chat_id' => 'required|exists:support_chats,id'
        ];
    }
}

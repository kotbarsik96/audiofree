<?php

namespace App\Http\Requests\SupportChat;

use App\Http\Requests\SupportChat\SupportChatBaseRequest;

class SupportChatUpdateWritingStatusRequest extends SupportChatBaseRequest
{
    public function rules(): array
    {
        return [
            'chat_id' => 'nullable|exists:support_chats,id',
            'is_writing' => 'boolean'
        ];
    }
}

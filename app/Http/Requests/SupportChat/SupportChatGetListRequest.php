<?php

namespace App\Http\Requests\SupportChat;

use App\Http\Requests\SupportChat\SupportChatBaseRequest;

class SupportChatGetListRequest extends SupportChatBaseRequest
{
    public function rules(): array
    {
        return [
            'chat_id' => 'nullable|exists:support_chats,id',
            'earliest_message_id' => 'nullable|exists:support_chat_messages,id',
            'latest_message_id' => 'nullable|exists:support_chat_messages,id',
        ];
    }
}

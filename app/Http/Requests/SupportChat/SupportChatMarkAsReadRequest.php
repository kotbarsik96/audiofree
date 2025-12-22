<?php

namespace App\Http\Requests\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Http\Requests\SupportChat\SupportChatBaseRequest;
use App\Models\SupportChat;
use App\Models\SupportChatMessage;
use Closure;

class SupportChatMarkAsReadRequest extends SupportChatBaseRequest
{
    public SupportChat|null $chat = null;
    public SupportChatMessage|null $firstReadMessage = null;

    public function prepareForValidation()
    {
        $this->chat = $this->chat_id ? SupportChat::find($this->chat_id) : auth()->user()->supportChat;

        $this->firstReadMessage = SupportChatMessage::find($this->first_read_message_id);
    }

    public function rules(): array
    {
        return [
            'chat_id' => 'nullable|exists:support_chats,id',
            // id первого прочитанного сообщения. Должно быть сообщением от собеседника
            'first_read_message_id' => [
                'required',
                'exists:support_chat_messages,id',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($this->firstReadMessage?->chat_id !== $this->chat->id) {
                        $fail('Необходимо сообщение из текущего чата');
                        return;
                    }

                    if ($this->getCurrentSenderType()->value === $this->firstReadMessage?->sender_type) {
                        $fail('Необходимо сообщение от собеседника');
                        return;
                    }
                }
            ],
            // сколько сообщений всего прочитано (включая первое)
            'read_count' => 'required|integer|min:1'
        ];
    }
}

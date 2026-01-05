<?php

namespace App\Http\Requests\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Http\Requests\SupportChat\SupportChatBaseRequest;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use Closure;

class SupportChatMarkAsReadRequest extends SupportChatBaseRequest
{
    public SupportChatMessage|null $firstReadMessage = null;

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $this->chat = $this->chat_id
            ? SupportChat::find($this->chat_id)
            : auth()->user()->supportChat;
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
                        $fail(__('abortions.requiredMessageFromCurrentChat'));
                        return;
                    }

                    if ($this->getCurrentSenderType()->value === $this->firstReadMessage?->sender_type) {
                        $fail(__('abortions.requiredMessageFromCompanion'));
                        return;
                    }
                }
            ],
            // сколько сообщений всего прочитано (включая первое)
            'read_count' => 'required|integer|min:1'
        ];
    }
}

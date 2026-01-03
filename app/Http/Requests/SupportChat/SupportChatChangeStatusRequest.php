<?php

namespace App\Http\Requests\SupportChat;

use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Http\Requests\SupportChat\SupportChatBaseRequest;
use Illuminate\Validation\Rule;

class SupportChatChangeStatusRequest extends SupportChatBaseRequest
{
    public function authorize(): bool
    {
        return $this->authorizeStaff();
    }

    public function rules(): array
    {
        return [
            'chat_id' => 'required|exists:support_chats,id',
            'status' => [Rule::enum(SupportChatStatusesEnum::class)]
        ];
    }
}

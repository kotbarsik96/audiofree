<?php

namespace App\Rules\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MarkAsRead implements ValidationRule
{
    public function __construct(
        private SupportChat $chat,
        private SupportChatMessage $firstReadMessage,
        private SupportChatSenderTypeEnum $senderType
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->firstReadMessage?->chat_id !== $this->chat->id) {
            $fail(__('abortions.requiredMessageFromCurrentChat'));
            return;
        }

        if ($this->senderType->value === $this->firstReadMessage?->sender_type) {
            $fail(__('abortions.requiredMessageFromCompanion'));
            return;
        }
    }
}

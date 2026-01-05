<?php

namespace App\Services\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Events\SupportChat\SupportChatChangeInfoEvent;
use App\Events\SupportChat\SupportChatReadEvent;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use Carbon\Carbon;

class SupportChatService
{
    public function __construct()
    {
    }

    public function writeMessage(SupportChat $chat, SupportChatSenderTypeEnum $senderType, string $text)
    {
        $user = auth()->user();
        $chat->setOpenStatus();

        $updatedIds = $chat->unreadMessagesFromCompanion($senderType)
            ->select('id')
            ->get()
            ->pluck('id');
        $chat->unreadMessagesFromCompanion($senderType)
            ->update([
                'read_at' => Carbon::now()
            ]);

        SupportChatReadEvent::dispatch($updatedIds, $chat, auth()->user());

        return SupportChatMessage::create([
            'chat_id' => $chat->id,
            'author_id' => $user->id,
            'sender_type' => $senderType,
            'text' => $text,
        ]);
    }

    public function writeSystemMessage(
        SupportChat $chat,
        string $text,
        int $userId,
        array|null $replacesUser = null,
        array|null $replacesStaff = null
    ) {
        return SupportChatMessage::create([
            'chat_id' => $chat->id,
            'author_id' => $userId,
            'sender_type' => SupportChatSenderTypeEnum::SYSTEM->value,
            'text' => $text,
            'replaces_user' => $replacesUser,
            'replaces_staff' => $replacesStaff,
        ]);
    }

    public function changeChatStatus(SupportChat $chat, SupportChatStatusesEnum $status)
    {
        $shouldChange = $status->value !== $chat->status;

        if ($shouldChange) {
            $chat->update([
                'status' => $status->value
            ]);

            SupportChatChangeInfoEvent::dispatch($chat);

            if ($status === SupportChatStatusesEnum::OPEN)
                $this->writeSystemMessage($chat, __('chat.opened'), auth()->user()->id);
            if ($status === SupportChatStatusesEnum::CLOSED)
                $this->writeSystemMessage($chat, __('chat.closed'), auth()->user()->id);
        }

        return $shouldChange;
    }
}
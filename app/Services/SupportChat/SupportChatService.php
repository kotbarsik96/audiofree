<?php

namespace App\Services\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Events\SupportChat\BroadcastsToStaff\ChangedInfoStaff;
use App\Events\SupportChat\BroadcastsToStaff\ReadMessageStaff;
use App\Events\SupportChat\BroadcastsToUser\ChangedInfoUser;
use App\Events\SupportChat\BroadcastsToUser\ReadMessageUser;
use App\Events\SupportChat\ChangeWritingStatusEvent;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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

        if ($chat->user_id === $user->id)
            ReadMessageStaff::dispatch($updatedIds, $chat, $user);
        else {
            ReadMessageStaff::dispatch($updatedIds, $chat, $user);
            ReadMessageUser::dispatch($updatedIds, $chat, $user);
        }

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

            ChangedInfoStaff::dispatch($chat);
            ChangedInfoUser::dispatch($chat);

            if ($status === SupportChatStatusesEnum::OPEN)
                $this->writeSystemMessage($chat, __('chat.opened'), auth()->user()->id);
            if ($status === SupportChatStatusesEnum::CLOSED)
                $this->writeSystemMessage($chat, __('chat.closed'), auth()->user()->id);
        }

        return $shouldChange;
    }

    public function markMessagesAsRead(
        SupportChat $chat,
        SupportChatMessage $firstReadMessage,
        int $readCount,
        SupportChatSenderTypeEnum $senderType
    ) {
        // не совпадают чаты
        throw_if(
            $firstReadMessage?->chat_id !== $chat->id,
            new UnprocessableEntityHttpException(__('abortions.requiredMessageFromCurrentChat'))
        );

        // первое сообщение не от собеседника
        throw_if(
            $senderType === $firstReadMessage->sender_type,
            new UnprocessableEntityHttpException(__('abortions.requiredMessageFromCompanion'))
        );

        $builder = $chat->unreadMessagesFromCompanion($senderType)
            ->where('created_at', '>=', $firstReadMessage->created_at)
            ->limit($readCount);
        $updatedIds = $builder->clone()->select('id')->get()->pluck('id');
        $updated = $builder->update([
            'read_at' => Carbon::now()
        ]);

        $user = auth()->user();

        if ($chat->id === $user->id)
            ReadMessageStaff::dispatch($updatedIds, $chat, $user);
        else {
            ReadMessageStaff::dispatch($updatedIds, $chat, $user);
            ReadMessageUser::dispatch($updatedIds, $chat, $user);
        }

        return $updated;
    }

    public function updateWritingStatus(SupportChat $chat, bool $isWriting)
    {
        ChangeWritingStatusEvent::dispatch($chat, auth()->user(), $isWriting);
    }
}
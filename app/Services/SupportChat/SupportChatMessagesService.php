<?php

namespace App\Services\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Http\Resources\SupportChat\SupportChatMessageResource;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SupportChatMessagesService
{
    public int|null $earliest_message_id = null;
    public int|null $latest_message_id = null;

    public Collection $messages;

    public function __construct(
        protected $limit = 30
    ) {
    }

    public function collectMessages(
        SupportChat $chat,
        SupportChatSenderTypeEnum $senderType,
        int|null $earliestMessageId = null,
        int|null $latestMessageId = null,
        bool $loadAll = false
    ) {
        if ($earliestMessageId) {
            $this->messages = $this->getPreviousMessages($chat, $earliestMessageId, $loadAll);
        } elseif ($latestMessageId) {
            $this->messages = $this->getNextMessages($chat, $latestMessageId, $loadAll);
        } else {
            $this->messages = $this->getInitialMessages($chat, $senderType);
        }

        $this->messages = $this->messages->map(
            fn($message) => (new SupportChatMessageResource($message))
                ->setSenderType($senderType)
        );

        $messagesCount = $this->messages->count();
        $this->earliest_message_id = $messagesCount > 0 ? $this->messages[0]?->id : null;
        $this->latest_message_id = $messagesCount > 0 ? $this->messages[$messagesCount - 1]?->id : null;

        return $this;
    }

    public function getPreviousMessages(
        SupportChat $chat,
        int|null $earliestMessageId = null,
        bool $loadAll = false
    ) {
        $earliestMessage = SupportChatMessage::find($earliestMessageId);

        $builder = SupportChatMessage::when($earliestMessage, function (Builder $query) use ($chat, $earliestMessage) {
            $query->where('chat_id', $chat->id)
                ->where('created_at', '<', $earliestMessage->created_at)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc');
        })->when(!$loadAll, function (Builder $query) {
            // если нужно загрузить все оставшиеся - не выставлять лимит
            $query->limit($this->limit);
        });

        return $builder->get()
            ->reverse()
            ->values();
    }

    public function getNextMessages(
        SupportChat $chat,
        int|null $latestMessageId = null,
        bool $loadAll = false
    ) {
        $latestMessage = SupportChatMessage::find($latestMessageId);

        $builder = SupportChatMessage::when($latestMessage, function (Builder $query) use ($chat, $latestMessage) {
            $query->where('chat_id', $chat->id)
                ->where('created_at', '>', $latestMessage->created_at);
        })->when(!$loadAll, function (Builder $query) {
            // если нужно загрузить все оставшиеся - не выставлять лимит
            $query->limit($this->limit);
        });

        return $builder
            ->get()
            ->values();
    }

    public function getInitialMessages(SupportChat $chat, SupportChatSenderTypeEnum $senderType)
    {
        $oldestUnreadMessage = $chat->unreadMessagesFromCompanion($senderType)->first();

        $builder = SupportChatMessage::when($oldestUnreadMessage, function (Builder $query) use ($chat, $oldestUnreadMessage) {
            // загрузить сообщения до первого непрочитанного (включительно) и после первого непрочитанного
            $query->where('chat_id', $chat->id)
                ->where('created_at', '<', $oldestUnreadMessage->created_at)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->limit(3);
        })->when(!$oldestUnreadMessage, function (Builder $query) use ($chat) {
            // загрузить последние $limit сообщений
            $query->where('chat_id', $chat->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->limit($this->limit);
        });

        if ($oldestUnreadMessage) {
            return $builder->get()
                ->reverse()
                ->concat(
                    SupportChatMessage::where('chat_id', $chat->id)
                        ->where('created_at', '>=', $oldestUnreadMessage->created_at)
                        ->limit($this->limit)
                        ->get()
                )
                ->values();
        } else {
            return $builder->get()
                ->reverse()
                ->values();
        }
    }
}
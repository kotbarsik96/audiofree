<?php

namespace App\Events\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatWritingStatus;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportChatWriteStatusEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private SupportChatSenderTypeEnum|null $sender = null;
    /**
     * Create a new event instance.
     */
    public function __construct(
        private SupportChatWritingStatus $status
    ) {
        $this->sender = $status->chat->user_id === $status->writer_id
            ? SupportChatSenderTypeEnum::USER
            : SupportChatSenderTypeEnum::STAFF;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->sender === SupportChatSenderTypeEnum::USER)
            return [
                new PrivateChannel('support-chat-staff.'.$this->status->chat->id),
                new PrivateChannel('support-chats-list')
            ];

        return [new PrivateChannel('support-chat-user.'.$this->status->chat->user->id)];
    }

    public function broadcastAs()
    {
        return 'support-chat-write-status';
    }

    public function broadcastWith()
    {
        return [
            'is_writing' => $this->status->isWriting(),
            'sender' => $this->sender->value,
            'chat_info' => $this->status->chat->getInfo($this->sender)
        ];
    }
}

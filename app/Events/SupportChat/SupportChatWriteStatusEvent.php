<?php

namespace App\Events\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat;
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

    /**
     * Create a new event instance.
     */
    public function __construct(
        public bool $is_writing,
        private SupportChatSenderTypeEnum $sender,
        private SupportChat $chat
    ) {
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
                new PrivateChannel('support-chat-staff.'.$this->chat->id),
                new PrivateChannel('support-chats-list')
            ];

        return [new PrivateChannel('support-chat-user.'.$this->chat->user->id)];
    }

    public function broadcastAs()
    {
        return 'support-chat-write-status';
    }

    public function broadcastWith()
    {
        return [
            'is_writing' => $this->is_writing,
            'sender' => $this->sender->value,
            'chat_id' => $this->chat->id
        ];
    }
}

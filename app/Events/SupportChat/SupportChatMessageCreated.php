<?php

namespace App\Events\SupportChat;
use App\Models\SupportChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportChatMessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public SupportChatMessage $message)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support-chat-staff.'.$this->message->chat->id),
            new PrivateChannel('support-chat-user.'.$this->message->chat->user_id),
            new PrivateChannel('support-chats-list'),
        ];
    }

    public function broadcastAs()
    {
        return 'support-chat-message-created';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'chat_info' => $this->message->chat
        ];
    }
}

<?php

namespace App\Events\SupportChat;

use App\Models\SupportChat\SupportChat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeWritingStatusEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public SupportChat $chat, public User $writer, public bool $isWriting)
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
            new PrivateChannel('support-chats-list'),
            new PresenceChannel('support-chat.'.$this->chat->id)
        ];
    }

    public function broadcastAs()
    {
        return 'support-chat-writing-status';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->writer->id,
            'name' => $this->writer->name,
            'chat_id' => $this->chat->id,
            'is_writing' => $this->isWriting
        ];
    }
}

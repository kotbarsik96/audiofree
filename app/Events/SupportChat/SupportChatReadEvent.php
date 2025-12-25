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

class SupportChatReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param $chat - передаётся, если прочитано пользователем
     * @param $user - передаётся, если прочитано сотрудником
     */
    public function __construct(public array $readMessagesIds, private SupportChat|null $chat, private User|null $user)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->chat)
            return [new PrivateChannel('support-chat-staff.'.$this->chat->id)];

        return [new PrivateChannel('support-chat-user.'.$this->user->id)];
    }

    public function broadcastAs()
    {
        return 'support-chat-read';
    }
}

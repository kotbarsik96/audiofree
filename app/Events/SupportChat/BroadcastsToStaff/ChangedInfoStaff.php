<?php

namespace App\Events\SupportChat\BroadcastsToStaff;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Http\Resources\SupportChat\SupportChatInfoResource;
use App\Models\SupportChat\SupportChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangedInfoStaff implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public SupportChat $chat)
    {
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support-chats-list'),
            new PrivateChannel('support-chat-staff.'.$this->chat->id)
        ];
    }

    public function broadcastAs()
    {
        return 'support-chat-changed-info';
    }

    public function broadcastWith()
    {
        return [
            'chat_info' => (new SupportChatInfoResource($this->chat))->setSenderType(SupportChatSenderTypeEnum::STAFF)
        ];
    }
}

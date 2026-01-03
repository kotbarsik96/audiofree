<?php

namespace App\Events\SupportChat\BroadcastsToStaff;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Http\Resources\SupportChat\SupportChatMessageResource;
use App\Models\SupportChat\SupportChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageStaff implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public SupportChatMessage $message)
    {
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support-chats-list'),
            new PrivateChannel('support-chat-staff.'.$this->message->chat->id)
        ];
    }

    public function broadcastAs()
    {
        return 'support-chat-message-created';
    }

    public function broadcastWith()
    {
        return [
            'message' => (new SupportChatMessageResource($this->message))->setSenderType(SupportChatSenderTypeEnum::STAFF),
            'chat_info' => $this->message->chat->getInfo(SupportChatSenderTypeEnum::STAFF)
        ];
    }
}

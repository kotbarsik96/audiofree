<?php

namespace App\Events\SupportChat\BroadcastsToStaff;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReadMessageStaff implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $readMessagesIds, public SupportChat $chat, public User $reader)
    {
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support-chat-staff.'.$this->chat->id)
        ];
    }

    public function broadcastAs()
    {
        return 'support-chat-read';
    }

    public function broadcastWith()
    {
        return [
            'read_messages_ids' => $this->readMessagesIds,
            'chat_info' => $this->chat->getInfo(SupportChatSenderTypeEnum::STAFF),
            'reader_id' => $this->reader->id
        ];
    }
}

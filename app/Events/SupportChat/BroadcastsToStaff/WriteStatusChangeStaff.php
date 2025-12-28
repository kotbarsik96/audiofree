<?php

namespace App\Events\SupportChat\BroadcastsToStaff;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChatWritingStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WriteStatusChangeStaff implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public SupportChatWritingStatus $status)
    {
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support-chat-staff.'.$this->status->chat->id),
            new PrivateChannel('support-chats-list')
        ];
    }

    public function broadcastAs()
    {
        return 'support-chat-write-status';
    }

    public function broadcastWith()
    {
        return [
            'writer_id' => $this->status->writer_id,
            'chat_info' => $this->status->chat->getInfo(SupportChatSenderTypeEnum::STAFF)
        ];
    }
}

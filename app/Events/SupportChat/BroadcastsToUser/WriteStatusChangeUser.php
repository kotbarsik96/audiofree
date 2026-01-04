<?php

namespace App\Events\SupportChat\BroadcastsToUser;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Http\Resources\SupportChat\SupportChatInfoResource;
use App\Models\SupportChat\SupportChatWritingStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WriteStatusChangeUser implements ShouldBroadcast
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
            new PrivateChannel('support-chat-user.'.$this->status->chat->user->id)
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
            'chat_info' => (new SupportChatInfoResource($this->status->chat))->setSenderType(SupportChatSenderTypeEnum::USER)
        ];
    }
}

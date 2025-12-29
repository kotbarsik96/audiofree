<?php

namespace App\Events\SupportChat\BroadcastsToUser;

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

class ReadMessageUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public iterable $readMessagesIds, public SupportChat $chat, public User $reader)
    {
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support-chat-user.'.$this->chat->user->id)
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
            'chat_info' => $this->chat->getInfo(SupportChatSenderTypeEnum::USER),
            'reader_id' => $this->reader->id
        ];
    }
}

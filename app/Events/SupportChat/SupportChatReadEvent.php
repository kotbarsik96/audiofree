<?php

namespace App\Events\SupportChat;

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

class SupportChatReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * кому отправляется уведомление о прочтении
     */
    private SupportChatSenderTypeEnum|null $companionSenderType = null;

    public function __construct(public array $readMessagesIds, private SupportChat $chat, private User $reader)
    {
        $this->companionSenderType = $chat->user_id === $reader->id
            ? SupportChatSenderTypeEnum::STAFF
            : SupportChatSenderTypeEnum::USER;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->companionSenderType === SupportChatSenderTypeEnum::STAFF)
            return [new PrivateChannel('support-chat-staff.'.$this->chat->id)];

        return [new PrivateChannel('support-chat-user.'.$this->chat->user->id)];
    }

    public function broadcastAs()
    {
        return 'support-chat-read';
    }

    public function broadcastWith()
    {
        return [
            'read_messages_ids' => $this->readMessagesIds,
            'chat_info' => $this->chat->getInfo($this->companionSenderType)
        ];
    }
}

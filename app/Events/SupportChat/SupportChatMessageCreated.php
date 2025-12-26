<?php

namespace App\Events\SupportChat;
use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChatMessage;
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

    private SupportChatSenderTypeEnum|null $senderType = null;

    /**
     * Create a new event instance.
     */
    public function __construct(public SupportChatMessage $message)
    {
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // todo: не нравится, как это сейчас сделано. Подумать над тем, чтобы полностью разделять события
        $arr = [new PrivateChannel('support-chats-list')];

        if ($this->message->chat->user_id === auth()->user()->id)
            array_push($arr, new PrivateChannel('support-chat-staff.'.$this->message->chat->id));
        else
            array_push($arr, new PrivateChannel('support-chat-user.'.$this->message->chat->user_id));

        return $arr;
    }

    public function getReceiverType()
    {
        $user = auth()->user();

        if ($this->message->chat->user_id === $user->id)
            return SupportChatSenderTypeEnum::STAFF;
        else
            return SupportChatSenderTypeEnum::USER;
    }

    public function broadcastAs()
    {
        return 'support-chat-message-created';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'chat_info' => $this->message->chat->getInfo($this->getReceiverType())
        ];
    }
}

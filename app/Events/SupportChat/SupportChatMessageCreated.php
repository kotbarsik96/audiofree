<?php

namespace App\Events\SupportChat;
use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Events\SupportChat\BroadcastsToStaff\NewMessageStaff;
use App\Events\SupportChat\BroadcastsToUser\NewMessageUser;
use App\Models\SupportChat\SupportChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportChatMessageCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public SupportChatMessage $message)
    {
        NewMessageStaff::dispatch($message);
        NewMessageUser::dispatch($message);
    }
}

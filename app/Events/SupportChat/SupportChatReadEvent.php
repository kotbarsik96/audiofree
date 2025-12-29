<?php

namespace App\Events\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Events\SupportChat\BroadcastsToStaff\ReadMessageStaff;
use App\Events\SupportChat\BroadcastsToUser\ReadMessageUser;
use App\Models\SupportChat\SupportChat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportChatReadEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public iterable $readMessagesIds, private SupportChat $chat, private User $reader)
    {
        if ($chat->user_id === $reader->id)
            ReadMessageStaff::dispatch($readMessagesIds, $chat, $reader);
        else {
            ReadMessageStaff::dispatch($readMessagesIds, $chat, $reader);
            ReadMessageUser::dispatch($readMessagesIds, $chat, $reader);
        }
    }
}

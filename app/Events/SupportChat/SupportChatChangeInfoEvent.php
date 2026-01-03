<?php

namespace App\Events\SupportChat;

use App\Events\SupportChat\BroadcastsToStaff\ChangedInfoStaff;
use App\Events\SupportChat\BroadcastsToUser\ChangedInfoUser;
use App\Models\SupportChat\SupportChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportChatChangeInfoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public SupportChat $chat)
    {
        ChangedInfoStaff::dispatch($chat);
        ChangedInfoUser::dispatch($chat);
    }
}

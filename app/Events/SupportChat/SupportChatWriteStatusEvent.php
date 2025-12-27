<?php

namespace App\Events\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Events\SupportChat\BroadcastsToStaff\WriteStatusChangeStaff;
use App\Events\SupportChat\BroadcastsToUser\WriteStatusChangeUser;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatWritingStatus;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportChatWriteStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public SupportChatWritingStatus $status
    ) {
        if ($status->writer_id === $status->chat->user_id)
            WriteStatusChangeStaff::dispatch($status);
        else {
            WriteStatusChangeStaff::dispatch($status);
            WriteStatusChangeUser::dispatch($status);
        }
    }
}

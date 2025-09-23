<?php

namespace App\Events\SupportChat;

use App\Models\SupportChat\SupportChat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReadEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public function __construct(
    public array $readMessagesIds,
    public SupportChat $chat
  ) {
  }

  public function broadcastOn(): array
  {
    return [
      new PrivateChannel('support.message.'.$this->chat->id),
    ];
  }

  public function broadcastAs(): string
  {
    return 'support-read-message';
  }

  public function broadcastWith()
  {
    return $this->readMessagesIds;
  }
}

<?php

namespace App\Http\Resources\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChatMessage;
use App\Models\SupportChat\SupportChatWritingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportChatInfoResource extends JsonResource
{
    public SupportChatSenderTypeEnum $senderType;

    public function setSenderType(SupportChatSenderTypeEnum $senderType)
    {
        $this->senderType = $senderType;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'chat_id' => $this->id,
            'unread_messages' => $this->unreadMessagesFromCompanion($this->senderType)->count(),
            'total_messages' => $this->messages()->count(),
            'first_message_id' => SupportChatMessage::where('chat_id', $this->id)->first()?->id,
            'last_message_id' => SupportChatMessage::where('chat_id', $this->id)->orderBy('created_at', 'desc')->first()->id,
            'user_name' => $this->user->name,
            'status' => $this->status,
            'user_writing' => !!SupportChatWritingStatus::writingNow($this->id)
                ->where('writer_id', $this->user_id)
                ->first(),
            'staff_writing' => !!SupportChatWritingStatus::writingNowExceptUser($this->id, $this->user_id)
                ->first(),
            'staff_writers' => SupportChatWritingStatus::writingNowExceptUser($this->id, $this->user_id)
                ->with('writer:id,name')
                ->get()
                ->pluck('writer.name')
        ];
    }
}

<?php

namespace App\Models\SupportChat;

use App\DTO\SupportChatInfoDTO;
use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SupportChat extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'user_id',
        'status',
    ];

    protected $casts = [
        'is_writing' => 'boolean'
    ];

    public function messages()
    {
        return $this->hasMany(SupportChatMessage::class, 'chat_id');
    }

    public function unreadMessages()
    {
        return $this->messages()->whereNull('read_at');
    }

    public function oldestMessage()
    {
        return $this->hasOne(SupportChatMessage::class, 'chat_id')->oldestOfMany();
    }

    public function latestMessage()
    {
        return $this->hasOne(SupportChatMessage::class, 'chat_id')->latestOfMany();
    }

    public function unreadMessagesFromCompanion(SupportChatSenderTypeEnum $currentSender)
    {
        return $this->unreadMessages()->where('sender_type', '!=', $currentSender->value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getInfo(SupportChatSenderTypeEnum $senderType)
    {
        $data = new SupportChatInfoDTO(
            chat_id: $this->id,
            unread_messages: $this->unreadMessagesFromCompanion($senderType)->count(),
            total_messages: $this->messages()->count(),
            first_message_id: SupportChatMessage::where('chat_id', $this->id)->first()?->id,
            last_message_id: SupportChatMessage::where('chat_id', $this->id)->orderBy('created_at', 'desc')->first()->id,
            user_name: $this->user->name,
            user_writing: !!SupportChatWritingStatus::writingNow($this->id)
                ->where('writer_id', $this->user_id)
                ->first(),
            staff_writing: !!SupportChatWritingStatus::writingNowExceptUser($this->id, $this->user_id)
                ->first(),
            staff_writers: SupportChatWritingStatus::writingNowExceptUser($this->id, $this->user_id)
                ->with('writer:id,name')
                ->get()
                ->pluck('writer.name')
        );

        if ($senderType !== SupportChatSenderTypeEnum::STAFF)
            unset($data->staff_writers);

        return $data;
    }
}

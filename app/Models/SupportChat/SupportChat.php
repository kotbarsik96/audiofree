<?php

namespace App\Models\SupportChat;

use App\DTO\SupportChatInfoDTO;
use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
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
        'is_writing' => 'boolean',
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

    public function latest_message()
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

    public function setOpenStatus()
    {
        $changed = false;

        if ($this->status !== SupportChatStatusesEnum::OPEN)
            $changed = $this->update(['status' => SupportChatStatusesEnum::OPEN]);

        return $changed;
    }

    public function setClosedStatus()
    {
        $changed = false;

        if ($this->status !== SupportChatStatusesEnum::CLOSED)
            $changed = $this->update(['status' => SupportChatStatusesEnum::CLOSED]);

        return $changed;
    }

    public function scopeChatsList(Builder $query, int $userId)
    {
        return $query->addSelect([
            'support_chats.id',
            'support_chats.status',
            'support_chats.created_at',
            'support_chats.updated_at',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone_number as user_phone',
            'users.telegram as user_telegram',
            'unread_messages' => SupportChatMessage::selectRaw('count(*)')
                ->whereColumn('support_chat_messages.chat_id', 'support_chats.id')
                ->whereColumn('support_chat_messages.author_id', 'support_chats.user_id')
                ->whereNull('support_chat_messages.read_at')
        ])
            ->with('latest_message')
            ->withMax('messages as latest_message_created_at', 'created_at')
            ->join('users', 'users.id', '=', 'support_chats.user_id')
            ->orderBy('status', 'asc')
            ->orderBy('latest_message_created_at', 'desc');
    }
}

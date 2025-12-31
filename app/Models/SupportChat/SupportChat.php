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

    public function getInfo(SupportChatSenderTypeEnum $senderType)
    {
        $data = new SupportChatInfoDTO(
            chat_id: $this->id,
            unread_messages: $this->unreadMessagesFromCompanion($senderType)->count(),
            total_messages: $this->messages()->count(),
            first_message_id: SupportChatMessage::where('chat_id', $this->id)->first()?->id,
            last_message_id: SupportChatMessage::where('chat_id', $this->id)->orderBy('created_at', 'desc')->first()->id,
            user_name: $this->user->name,
            status: $this->status,
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

    public static function chatsList()
    {
        return static::select([
            'support_chats.id',
            'support_chats.status',
            'support_chats.created_at',
            'support_chats.updated_at',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone_number as user_phone',
            'users.telegram as user_telegram'
        ])
            ->addSelect([
                'latest_message_created_at' => SupportChatMessage::select('created_at')
                    ->whereColumn('support_chat_messages.chat_id', 'support_chats.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1),
                'writers_count' => SupportChatWritingStatus::selectRaw('count(*)')
                    ->whereNotNull('support_chat_writing_statuses.started_writing_at')
                    ->whereColumn('support_chat_writing_statuses.chat_id', 'support_chats.id')
                    ->where('support_chat_writing_statuses.writer_id', '!=', auth()->user()->id),
                'unread_messages' => SupportChatMessage::selectRaw('count(*)')
                    ->whereColumn('support_chat_messages.chat_id', 'support_chats.id')
                    ->whereColumn('support_chat_messages.author_id', 'support_chats.user_id')
                    ->whereNull('support_chat_messages.read_at')
            ])
            ->with('latest_message')
            ->join('users', 'users.id', '=', 'support_chats.user_id')
            ->orderBy('status', 'asc')
            ->orderBy('latest_message_created_at', 'desc');
    }

    public function writeSystemMessage(string $text, array|null $replacesUser = null, array|null $replacesStaff = null)
    {
        $msg = SupportChatMessage::create([
            'chat_id' => $this->id,
            'author_id' => auth()->user()->id,
            'sender_type' => SupportChatSenderTypeEnum::SYSTEM->value,
            'text' => $text,
            'replaces_user' => $replacesUser,
            'replaces_staff' => $replacesStaff,
        ]);
        return $msg;
    }
}

<?php

namespace App\Models\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Events\SupportChat\BroadcastsToStaff\NewMessageStaff;
use App\Events\SupportChat\BroadcastsToUser\NewMessageUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SupportChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'author_id',
        'sender_type',
        'text',
        'read_at',
        'edited_at',
        'replaces_user',
        'replaces_staff'
    ];

    protected $casts = [
        'replaces_user' => 'json',
        'replaces_staff' => 'json',
    ];

    protected $hidden = [
        'replaces_user',
        'replaces_staff',
        'author',
        'chat'
    ];

    public static function booted()
    {
        static::created(function (SupportChatMessage $message) {
            NewMessageStaff::dispatch($message);
            NewMessageUser::dispatch($message);
        });
    }

    public function scopeUnreadMessages(Builder $query)
    {
        return $query->whereNull('read_at');
    }

    public function chat()
    {
        return $this->belongsTo(SupportChat::class, 'chat_id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function isSystem()
    {
        return $this->sender_type === SupportChatSenderTypeEnum::SYSTEM->value;
    }
}

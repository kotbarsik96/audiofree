<?php

namespace App\Models\SupportChat;

use App\Casts\SupportChat\AsMessageText;
use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Events\SupportChat\SupportChatMessageCreated;

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
        'text' => AsMessageText::class
    ];

    protected $hidden = [
        'replaces_user',
        'replaces_staff',
        'author',
        'chat'
    ];

    protected $dispatchesEvents = [
        'created' => SupportChatMessageCreated::class
    ];

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

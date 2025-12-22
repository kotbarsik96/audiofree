<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Events\SupportChatMessage\SupportChatMessageCreated;

class SupportChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'author_id',
        'sender_type',
        'text',
        'read_at',
        'edited_at'
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
}

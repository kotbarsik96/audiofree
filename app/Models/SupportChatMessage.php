<?php

namespace App\Models;

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
        'edited_at'
    ];

    public function scopeUnreadMessages(Builder $query)
    {
        return $query->whereNull('read_at');
    }
}

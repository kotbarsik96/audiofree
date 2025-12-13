<?php

namespace App\Models;

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

    public function latesetMessage()
    {
        return $this->hasOne(SupportChatMessage::class, 'chat_id')->latestOfMany();
    }
}

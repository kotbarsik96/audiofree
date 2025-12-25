<?php

namespace App\Models\SupportChat;

use App\Events\SupportChat\SupportChatWriteStatusEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChatWritingStatus extends Model
{
    use HasFactory;

    protected $table = 'support_chat_writing_statuses';

    public $timestamps = false;

    protected $fillable = [
        'chat_id',
        'writer_id',
        'started_writing_at'
    ];

    protected $dispatchesEvents = [
        'updated' => SupportChatWriteStatusEvent::class
    ];

    public function chat()
    {
        return $this->hasOne(SupportChat::class, 'id', 'chat_id');
    }

    public function writer()
    {
        return $this->hasOne(User::class, 'id', 'writer_id');
    }

    public function isWriting()
    {
        return $this->started_writing_at !== null;
    }
}

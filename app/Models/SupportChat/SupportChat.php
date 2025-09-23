<?php

namespace App\Models\SupportChat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SupportChat extends Model
{
  use HasFactory;

  protected $table = 'support_chats';

  protected $fillable = [
    'user_id'
  ];

  protected $casts = [
    'by_user' => 'boolean',
    'was_read' => 'boolean'
  ];

  public function scopeChatHistory(Builder $query, $chatId)
  {
    return $query->select(
      'messages.id',
      'messages.message_text',
      DB::raw('IF(support_chats.user_id = messages.message_author, 1, 0) as by_user'),
      'users.name as author',
      'messages.created_at',
      'messages.updated_at',
      'messages.was_read'
    )
      ->where('support_chats.id', $chatId)
      ->join('support_chat_messages as messages', 'messages.chat_id', '=', 'support_chats.id')
      ->join('users', 'users.id', '=', 'messages.message_author')
      ->orderByDesc('created_at');
  }

  public function scopeChatsList(Builder $query)
  {
    return $query->select(
      'support_chats.id',
      'support_chats.user_id',
      'users.name as user_name',
      'users.email as user_email',
      'users.telegram as user_telegram',
    )
      ->where('user_id', '!=', auth()->user()->id)
      ->join('users', 'users.id', '=', 'support_chats.user_id');
  }
}

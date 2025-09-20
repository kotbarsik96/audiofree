<?php

namespace App\Models\SupportChat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChatMessage extends Model
{
  use HasFactory;

  protected $table = 'support_chat_messages';

  protected $fillable = [
    'chat_id',
    'message_author',
    'message_text',
  ];

  protected $casts = [
    'by_user' => 'boolean'
  ];
}

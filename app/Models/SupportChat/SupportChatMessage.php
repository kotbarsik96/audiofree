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
    'read_by_user',
    'read_by_supporter'
  ];

  protected $casts = [
    'by_user' => 'boolean'
  ];

  public function attrsToFront()
  {
    return $this->only([
      'id',
      'message_text',
      'by_user',
      'created_at',
      'updated_at'
    ]);
  }
}

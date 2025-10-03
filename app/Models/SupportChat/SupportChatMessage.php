<?php

namespace App\Models\SupportChat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

  public function chat()
  {
    return $this->hasOne(SupportChat::class, 'id', 'chat_id');
  }

  public function scopeUnreadMessages(Builder $query, SupportChat $chat)
  {
    $userId = auth()->user()->id;
    $ofCurrentUser = $chat->user_id === $userId;

    $returnQuery = null;

    if ($ofCurrentUser) {
      $returnQuery = $query
        ->where('chat_id', $chat->id)
        ->where('message_author', '!=', $userId)
        ->where(function ($query) {
          $query->where('was_read', '=', 0)
            ->orWhereNull('was_read');
        });
    } else {
      $returnQuery = $query->where('chat_id', $chat->id)
        ->where('message_author', '=', $chat->user_id)
        ->where(function ($query) {
          $query->where('was_read', '=', 0)
            ->orWhereNull('was_read');
        });
    }
    
    return $returnQuery;
  }

  public function fromFirstUnreadMessage(SupportChat $chat, SupportChatMessage $firstUnreadMessage, int $perPage)
  {
    $previousMessagesCount = SupportChatMessage::where('created_at', '<', $firstUnreadMessage->created_at)
      ->where('chat_id', $chat->id)
      ->count();
    $pagesBefore = floor($previousMessagesCount / $perPage);
    $totalPages = ceil(SupportChatMessage::where('chat_id', $chat->id)->count() / $perPage);
    $page = $totalPages - $pagesBefore;

    return SupportChat::chatHistory($chat->id)
      ->paginate(perPage: $perPage, page: $page);
  }
}

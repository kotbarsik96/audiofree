<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupportChat extends Model
{
  use HasFactory;

  protected $table = 'support_chat';

  protected $fillable = [
    'user_id',
    'message_author',
    'message_text',
  ];

  protected $casts = [
    'by_user' => 'boolean'
  ];

  public function scopeChatHistory(Builder $query, $userId)
  {
    return $query->select(
      'id',
      'message_text',
      DB::raw('IF(user_id = message_author, 1, 0) as by_user'),
      'created_at',
      'updated_at'
    )
      ->where('user_id', $userId)
      ->orderByDesc('created_at');
  }
}

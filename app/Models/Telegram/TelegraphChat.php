<?php

namespace App\Models\Telegram;

use DefStudio\Telegraph\Models\TelegraphChat as BaseModel;
use App\Models\User;

class TelegraphChat extends BaseModel
{
  protected $fillable = [
    'chat_id',
    'name',
    'user_id',
    'state',
    'data',
  ];

  protected $casts = [
    'data' => 'json'
  ];

  protected $table = 'telegraph_chats';

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function setState(string|null $state = null): static
  {
    $this->update([
      'state' => $state
    ]);

    return $this;
  }

  public function removeState(): static
  {
    return $this->setState(null);
  }
}

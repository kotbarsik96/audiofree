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
    'telegraph_bot_id',
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
    $this->update(['state' => $state]);

    return $this;
  }

  public function removeState(): static
  {
    return $this->setState(null);
  }

  public function setData($data): static
  {
    $this->update(['data' => $data]);
    return $this;
  }

  public function removeData(): static
  {
    return $this->setData(null);
  }

  public function getDataItem(string $key)
  {
    $data = $this->data;
    if (!$data)
      return null;

    return array_key_exists($key, $data) ? $data[$key] : null;
  }
}

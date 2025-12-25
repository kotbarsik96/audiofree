<?php

namespace App\DTO;

class SupportChatInfoDTO
{
  public function __construct(
    public int $chat_id,
    public int $unread_messages,
    public int $total_messages,
    public int $first_message_id,
    public int $last_message_id,
    public string $user_name,
    public bool $is_companion_writing
  ) {
  }
}
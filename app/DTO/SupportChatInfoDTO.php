<?php

namespace App\DTO;

use App\Enums\SupportChat\SupportChatStatusesEnum;

class SupportChatInfoDTO
{
  /**
   * 
   * @param int $chat_id
   * @param int $unread_messages количество непрочитанных сообщений
   * @param int $total_messages общее количество сообщенйи
   * @param int $first_message_id id первого сообщения в чате
   * @param int $last_message_id id последнего сообщения в чате
   * @param string $user_name имя пользователя, начавшего чат
   * @param bool $user_writing пишет ли пользователь сообщение в данный момент
   * @param bool $staff_writing пишет ли хотя бы один сотрудник сообщение в данный момент
   * @param iterable|null $staff_writers имена сотрудников, которые пишут сообщения в данный момент. Указывается только для сотрудников
   */
  public function __construct(
    public int $chat_id,
    public int $unread_messages,
    public int $total_messages,
    public int $first_message_id,
    public int $last_message_id,
    public string $user_name,
    public SupportChatStatusesEnum|string $status,
    public bool $user_writing,
    public bool $staff_writing,
    public iterable|null $staff_writers
  ) {
  }
}
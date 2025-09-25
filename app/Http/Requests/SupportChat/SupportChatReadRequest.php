<?php

namespace App\Http\Requests\SupportChat;

use App\Models\SupportChat\SupportChat;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\SupportChat\SupportChatMessage;

class SupportChatReadRequest extends FormRequest
{
  public ?SupportChat $chat;

  /**
   * id'шники только тех сообщений, которые автор запроса смог прочитать
   */
  public ?array $read_messages_ids;

  public function prepareForValidation()
  {
    $this->chat = SupportChat::find($this->get('chat_id'));
  }

  public function passedValidation()
  {
    $messagesQuery = SupportChatMessage::where('id', '>=', $this->get('first_message_id'))
      ->where('chat_id', $this->chat->id);

    $userId = auth()->user()->id;
    // сообщения от сотрудников прочитаны пользователем
    if ($this->chat->user_id === $userId) {
      $messagesQuery = $messagesQuery->where('message_author', '!=', $userId);
    }
    // сообщения от пользователя прочитаны сотрудником
    else {
      $messagesQuery = $messagesQuery->where('message_author', $userId);
    }

    $this->read_messages_ids = $messagesQuery
      ->limit($this->get('read_count'))
      ->get()
      ->pluck('id')
      ->toArray();
  }

  public function authorize(): bool
  {
    return $this->chat->user_id === auth()->user()->id || auth()->user()->hasAccess('support.supporter');
  }

  public function rules(): array
  {
    return [
      'chat_id' => ['required'],
      'first_message_id' => ['required', 'integer'],
      'read_count' => ['required', 'integer', 'min:1']
    ];
  }
}

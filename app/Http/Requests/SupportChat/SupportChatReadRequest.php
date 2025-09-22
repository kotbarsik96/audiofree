<?php

namespace App\Http\Requests\SupportChat;

use App\Models\SupportChat\SupportChat;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\SupportChat\SupportChatMessage;

class SupportChatReadRequest extends FormRequest
{
  public ?SupportChat $chat;

  /**
   * id'шники только тех сообщений, которые автор запроса мог прочитать
   */
  public ?array $messages_ids_safe;

  public function prepareForValidation()
  {
    $this->chat = SupportChat::find($this->get('chat_id'));
  }

  public function passedValidation()
  {
    $messagesQuery = SupportChatMessage::whereIn('id', $this->get('messages_ids'))
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

    $this->messages_ids_safe = $messagesQuery->get()->pluck('id')->toArray();
  }

  public function authorize(): bool
  {
    return $this->chat->user_id === auth()->user()->id || auth()->user()->hasAccess('support.supporter');
  }

  public function rules(): array
  {
    return [
      'chat_id' => ['required'],
      'messages_ids' => ['required', 'array']
    ];
  }
}

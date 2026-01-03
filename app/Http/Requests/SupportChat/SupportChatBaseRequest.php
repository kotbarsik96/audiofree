<?php

namespace App\Http\Requests\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChat;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SupportChatBaseRequest extends FormRequest
{
    /**
     * Свойство устанавливает, обязательно ли наличие параметра chat_id в запросе от имени сотрудника
     */
    private $chatIdRequiredForStaff = false;
    public SupportChat|null $chat;

    public function prepareForValidation()
    {
        if ($this->has('chat_id')) {
            $this->chat = SupportChat::find($this->chat_id);
            throw_if(!$this->chat, new NotFoundHttpException(__('abortions.chatNotFound')));
        }
    }

    public function authorize(): bool
    {
        return $this->has('chat_id') ? $this->authorizeStaff() : true;
    }

    public function authorizeStaff()
    {
        return auth()->user()->hasAccess('platform.systems.support');
    }

    public function rules(): array
    {
        return [];
    }

    public function messages()
    {
        return [
            'chat_id.exists' => 'Chat not found'
        ];
    }

    public function getCurrentSenderType()
    {
        return $this->has('chat_id') ? SupportChatSenderTypeEnum::STAFF : SupportChatSenderTypeEnum::USER;
    }
}

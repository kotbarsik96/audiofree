<?php

namespace App\Http\Requests\SupportChat;

use App\Filters\SupportChatsListFilter;
use App\Http\Requests\SupportChat\SupportChatBaseRequest;

class SupportChatGetListRequest extends SupportChatBaseRequest
{
    public SupportChatsListFilter|null $filterableRequest = null;

    public function authorize(): bool
    {
        return $this->authorizeStaff();
    }

    public function prepareForValidation()
    {
        $this->filterableRequest = new SupportChatsListFilter($this);
    }

    public function rules(): array
    {
        return [];
    }
}

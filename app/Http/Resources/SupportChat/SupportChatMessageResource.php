<?php

namespace App\Http\Resources\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChatMessage;
use App\Services\SupportChat\SupportChatMessageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportChatMessageResource extends JsonResource
{
    public SupportChatSenderTypeEnum $senderType;

    public function setSenderType(SupportChatSenderTypeEnum $senderType)
    {
        $this->senderType = $senderType;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $service = app(SupportChatMessageService::class);

        return array_merge(parent::toArray($request), [
            'text' => $service->replaceText($this->resource, $this->senderType)
        ]);
    }
}

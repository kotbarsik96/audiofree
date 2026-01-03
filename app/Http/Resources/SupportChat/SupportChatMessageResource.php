<?php

namespace App\Http\Resources\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\SupportChat\SupportChatMessage;
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
        return array_merge(parent::toArray($request), [
            'text' => $this->replaceText($this->resource)
        ]);
    }

    public function replaceText(SupportChatMessage $message): mixed
    {
        $modifiedValue = $message->text;

        if ($message->sender_type === SupportChatSenderTypeEnum::SYSTEM->value) {
            if ($this->senderType === SupportChatSenderTypeEnum::USER) {
                $modifiedValue = $this->replaceUser($modifiedValue, $message->replaces_user);
            } else {
                $modifiedValue = $this->replaceStaff($modifiedValue, $message->replaces_staff, $message->author->name);
            }

            $modifiedValue = str_replace(':__user_name:', $message->chat->user->name, $modifiedValue);
        }

        return $modifiedValue;
    }

    /** заменяет переменные в системных сообщениях (sender_type === SupportChatSenderTypeEnum::SYSTEM) для вывода сотруднику
     * 
     * зарезервированные переменные: 
     * * :__staff_name: - заменяется на строку "Сотрудник";
     * * :__user_name: - заменяется на имя пользователя чата
     * 
     * кроме зарезервированных, переменные передаются при создании сообщения в поле replaces_staff (например: { "user": "Пользователь" } заменит подстроку :user: на Пользователь)
     */
    public function replaceStaff(string $value, array|null $replacesStaff = null, string $authorName)
    {
        $_value = $value;

        if ($replacesStaff) {
            foreach ($replacesStaff as $replaceKey => $replaceValue) {
                $_value = str_replace(":$replaceKey:", $replaceValue, $_value);
            }
        }

        $_value = str_replace(':__staff_name:', $authorName, $_value);

        return $_value;
    }

    /** заменяет переменные в системных сообщениях (sender_type === SupportChatSenderTypeEnum::SYSTEM) для вывода пользователю
     * 
     * зарезервированные переменные: 
     * * :__staff_name: - заменяется на имя сотрудника;
     * * :__user_name: - заменяется на имя пользователя чата
     * 
     * кроме зарезервированных, переменные передаются при создании сообщения в поле replaces_user (например: { "user": "Пользователь" } заменит подстроку :user: на Пользователь)
     */
    public function replaceUser(string $value, array|null $replacesUser = null)
    {
        $_value = $value;

        if ($replacesUser) {
            foreach ($replacesUser as $replaceKey => $replaceValue) {
                $_value = str_replace(":$replaceKey:", $replaceValue, $_value);
            }
        }

        $_value = str_replace(':__staff_name:', __('chat.staff'), $_value);

        return $_value;
    }
}

<?php

namespace App\Casts\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AsMessageText extends AsMessageTextBase
{

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $modifiedValue = $value;

        if ($model->sender_type === SupportChatSenderTypeEnum::SYSTEM->value) {
            $isUser = $model->chat->user_id === auth()->user()->id;
            if ($isUser) {
                $modifiedValue = $this->replaceUser($modifiedValue, $model->replaces_user);
            } else {
                $modifiedValue = $this->replaceStaff($modifiedValue, $model->replaces_staff, $model->author->name);
            }

            $modifiedValue = str_replace(':__user_name:', $model->chat->user->name, $modifiedValue);
        }

        return $modifiedValue;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}

<?php

use App\Models\SupportChat\SupportChat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('support-chat-staff.{chatId}', function (User $user, int $chatId) {
    return $user->hasAccess('platform.systems.support');
});

Broadcast::channel('support-chat-user.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});

Broadcast::channel('support-chats-list', function (User $user) {
    return $user->hasAccess('platform.systems.support');
});

Broadcast::channel('support-chat.{chat}', function (User $user, SupportChat $chat) {
    if ($user->id === $chat->user_id || $user->hasAccess('platform.systems.support'))
        return [
            'name' => $user->name,
        ];

    return false;
});
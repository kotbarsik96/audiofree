<?php

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
<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('support-chat.{chatId}', function (User $user, int $chatId) {
    return $user->hasAccess('platform.systems.support');
});

Broadcast::channel('support-chat', function (User $user) {
    return !!$user->supportChat->id;
});

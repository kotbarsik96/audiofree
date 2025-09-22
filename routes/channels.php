<?php

use App\Models\SupportChat\SupportChat;
use App\Models\User;

Broadcast::channel('support.message.{chatId}', function (User $user, $chatId) {
  $chat = SupportChat::find($chatId);
  return $user->id === $chat->user_id || $user->hasAccess('support.supporter');
});
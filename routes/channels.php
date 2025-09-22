<?php

use App\Models\SupportChat\SupportChat;

Broadcast::channel('support.message.{chatId}', function (User $user, int $chatId) {
  $chat = SupportChat::find($chatId);
  return $user->id === $chat->user_id || $user->hasAccess('support.supporter');
});
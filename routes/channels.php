<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users.{id}', function ($user, $id) {
    \Illuminate\Support\Facades\Log::info($id);
    return (int) $user->id === (int) $id;
});

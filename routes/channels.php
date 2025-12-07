<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users.{id}', function ($id) {
    return true;
});

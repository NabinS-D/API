<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('user.{id}', function ($user, $userId) {
    return $user ? $user->id === (int) $userId : false;
});



Broadcast::channel('taskchannel.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
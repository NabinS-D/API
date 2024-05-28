<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('user.{userId}', function ($user, $userId) {    //project notification
    return $user->id === (int) $userId;
});



Broadcast::channel('taskchannel.{userId}', function ($user, $userId) {              //task notification
    return (int) $user->id === (int) $userId;
});
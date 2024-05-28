<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notifications', function () {
    return view('notificationsproject');
});

Route::get('/notifi', function () {
    return view('notificationstask');
});
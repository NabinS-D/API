<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProjectManagerController;
use App\Http\Controllers\Api\TaskManagerController;
use App\Http\Controllers\Api\FilteringController;


Route::post('users/login', [UserController::class, 'login']);
Route::post('users/register', [UserController::class, 'register']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    
    Route::resource('projects', 'App\Http\Controllers\Api\ProjectManagerController');
    Route::get('/projectupdatehistory/{id}', [ProjectManagerController::class,'shownotes']);

    Route::resource('tasks', 'App\Http\Controllers\Api\TaskManagerController');
    Route::get('/taskupdatehistory/{id}', [TaskManagerController::class,'shownotes']);
    Route::get('/userdashboard', [TaskManagerController::class,'userdashboard']);

    Route::get('/filterpage', [FilteringController::class,'displaytasks']);
    Route::post('/filtering', [FilteringController::class,'filter']);

    Route::post('users/logout', [UserController::class, 'logout']);
});



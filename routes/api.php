<?php

use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum','web')->group(function () {
    Route::post('/user', [UserController::class, 'storeUser']);   // Store user
    Route::put('/user/{user}', [UserController::class, 'updateUser']);  // Update user
    Route::delete('/user/{user}', [UserController::class, 'deleteUser']);  // Update user
});
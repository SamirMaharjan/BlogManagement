<?php

use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Blog\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum','web')->group(function () {
    Route::post('/user', [UserController::class, 'storeUser']);   // Store user
    Route::put('/user/{user}', [UserController::class, 'updateUser']);  // Update user
    Route::delete('/user/{user}', [UserController::class, 'deleteUser']);  // Update user
    
    Route::post('/blog', [BlogController::class, 'storeBlog']);   // Store blog
    Route::put('/blog/{blog}', [BlogController::class, 'updateBlog']);  // Update blog
    Route::delete('/blog/{blog}', [BlogController::class, 'deleteBlog']);  // Update user
});
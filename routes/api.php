<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Blog\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']); // Login API

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // Logout API
    Route::get('/auth-user', [AuthController::class, 'user']); // Get logged-in user
});

Route::middleware('auth:sanctum','web')->group(function () {
    Route::get('/user', [UserController::class, 'list']);   // Store user
    Route::post('/user', [UserController::class, 'storeUser']);   // Store user
    Route::put('/user/{user}', [UserController::class, 'updateUser']);  // Update user
    Route::delete('/user/{user}', [UserController::class, 'deleteUser']);  // Update user
    Route::get('/blog', [BlogController::class, 'list']);  
    Route::post('/blog', [BlogController::class, 'storeBlog']);   // Store blog
    Route::put('/blog/{blog}', [BlogController::class, 'updateBlog']);  // Update blog
    Route::delete('/blog/{blog}', [BlogController::class, 'deleteBlog']);  // Update user
});
<?php

use App\Http\Controllers\Api\Blog\BlogController as BlogBlogController;
use App\Http\Controllers\Api\User\UserController as UserUserController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/user',[UserController::class,'userList'])->name('users.index');
Route::get('/blog',[BlogController::class,'blogList'])->name('blogs.index');

Route::post('/user', [UserUserController::class, 'storeUser']);   // Store user
Route::put('/user/{user}', [UserUserController::class, 'updateUser']);  // Update user
Route::delete('/user/{user}', [UserController::class, 'deleteUser']);  // Update user

Route::post('/blog', [BlogBlogController::class, 'storeBlog']);   // Store blog
Route::put('/blog/{blog}', [BlogBlogController::class, 'updateBlog']);  // Update blog
Route::delete('/blog/{blog}', [BlogBlogController::class, 'deleteBlog']);  // Update user

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

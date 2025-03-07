<?php

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
// Route::get('/user-create',[UserController::class,'createUser'])->name('users.create');
// Route::post('/user-store',[UserController::class,'storeUser'])->name('users.store');
// Route::put('/user/update',[UserController::class,'storeUser'])->name('users.update');
// Route::get('/user/{id}',[UserController::class,'userList'])->name('users.show');
// Route::get('/user-edit/{id}',[UserController::class,'userEdit'])->name('users.edit');
// Route::get('/user-delete/{id}',[UserController::class,'userDelete'])->name('users.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

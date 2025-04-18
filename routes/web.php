<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');

    Route::get('/users/{id}/edit-password', [AdminUserController::class, 'editPassword'])->name('admin.users.password');
    Route::post('/users/{id}/update-password', [AdminUserController::class, 'updatePassword'])->name('admin.users.updatePassword');
});

require __DIR__.'/auth.php';

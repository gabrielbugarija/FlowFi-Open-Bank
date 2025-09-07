<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public home page
Route::view('/', 'home')->name('home');

// Keep Breeze auth routes
require __DIR__.'/auth.php';

// Auth-only pages
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);

    // (Optional) Breeze profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

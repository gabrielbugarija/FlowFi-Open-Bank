<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Public home page
Route::view('/', 'home')->name('home');

// Keep Breeze auth routes
require __DIR__.'/auth.php';

// Auth-only pages
Route::middleware(['auth'])->group(function () {
    // /dashboard still lands users on accounts after login
    Route::get('/dashboard', fn () => redirect()->route('accounts.index'))
        ->middleware('verified')
        ->name('dashboard');

    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // (Optional) Breeze profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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

      Route::resource('accounts', AccountController::class);
      Route::resource('transactions', TransactionController::class);

    // (Optional) Breeze profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

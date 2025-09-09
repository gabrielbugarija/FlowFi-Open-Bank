<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaidController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard/monthly-totals', [DashboardController::class, 'monthlyTotals']);
    Route::get('/dashboard/category-totals', [DashboardController::class, 'categoryTotals']);
});

Route::get('/plaid/link-token', [PlaidController::class, 'linkToken']);
Route::post('/plaid/webhook', [PlaidController::class, 'webhook']);

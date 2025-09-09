<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaidController;
use App\Http\Controllers\DashboardController;

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/plaid/link-token', [PlaidController::class, 'linkToken']);
Route::post('/plaid/webhook', [PlaidController::class, 'webhook']);

Route::get('/dashboard/monthly-totals', [DashboardController::class, 'monthlyTotals']);
Route::get('/dashboard/weekly-totals', [DashboardController::class, 'weeklyTotals']);
Route::get('/dashboard/daily-totals', [DashboardController::class, 'dailyTotals']);
Route::get('/dashboard/category-totals', [DashboardController::class, 'categoryTotals']);

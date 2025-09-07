<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaidController;
use App\Http\Controllers\BudgetController;

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/budgets', [BudgetController::class, 'index']);
    Route::post('/budgets', [BudgetController::class, 'store']);
});

Route::get('/plaid/link-token', [PlaidController::class, 'linkToken']);
Route::post('/plaid/webhook', [PlaidController::class, 'webhook']);

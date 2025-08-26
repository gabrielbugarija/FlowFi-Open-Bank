<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaidController;

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/plaid/link-token', [PlaidController::class, 'linkToken']);
Route::post('/plaid/webhook', [PlaidController::class, 'webhook']);

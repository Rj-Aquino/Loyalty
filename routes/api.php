<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


use App\Http\Controllers\TokenController;

Route::post('/generate-token', [TokenController::class, 'generateToken']);

use App\Http\Controllers\LoyaltyCardsController;

// Protect these routes using Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('loyalty-cards', LoyaltyCardsController::class);
});

// In app/Http/Middleware/HandleCors.php or routes/api.php
return $next($request)->header('Access-Control-Allow-Origin', '*')
                      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
                      ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

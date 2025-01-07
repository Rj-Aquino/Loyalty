<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoyaltyCardsController;

// Default
Route::get('/', function () {
    return view('home');
});

Route::get('/apply', function () {
    return view('addloyaltycard');
});

Route::get('/viewpoints', function () {
    return view('viewpoints');
});

Route::post('/apply', [LoyaltyCardsController::class, 'addLoyaltyCard']);
Route::post('/viewpoints', [LoyaltyCardsController::class, 'viewPoints']);

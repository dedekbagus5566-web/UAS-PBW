<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/auctions/{auction}', [AuctionController::class, 'show']);

Route::middleware('auth.api')->group(function () {
    Route::post('/auctions', [AuctionController::class, 'store']);
    Route::get('/my-auctions', [AuctionController::class, 'myAuctions']);
});

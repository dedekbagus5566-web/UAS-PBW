<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BidController;
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/auctions/{auction}', [AuctionController::class, 'show']);
Route::get(
    '/auctions/{id}/bids',
    [BidController::class,'index']
);
Route::post('/auctions/{id}/close', [AuctionController::class, 'close']);
Route::middleware('auth.api')->group(function () {
    Route::post('/auctions', [AuctionController::class, 'store']);
    Route::get('/my-auctions', [AuctionController::class, 'myAuctions']);
    Route::post(
    '/auctions/{id}/bid',
    [BidController::class,'store']
);
});

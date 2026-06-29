<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\AuthController;



Route::post('/login',
[AuthController::class,'login']);



Route::post('/register',
[AuthController::class,'register']);



// PUBLIC AUCTION

Route::get('/auctions',
[AuctionController::class,'index']);


Route::get('/auctions/{id}',
[AuctionController::class,'show']);



Route::get('/auctions/{id}/bids',
[BidController::class,'index']);




// AUTH

Route::middleware('auth:sanctum')->group(function(){


    Route::post(
        '/auctions',
        [AuctionController::class,'store']
    );


    Route::post(
        '/bids',
        [BidController::class,'store']
    );


});
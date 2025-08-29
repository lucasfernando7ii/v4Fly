<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TravelOrderController;

Route::post('/login', [AuthController::class, 'login']);
Route::post("/register", [AuthController::class, "register"]);
Route::middleware('auth:sanctum')->group(function () {


    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/logout', [AuthController::class, 'logout']);


    Route::apiResource('travel-orders', TravelOrderController::class);


    Route::patch('travel-orders/{travelOrder}/status', [TravelOrderController::class, 'updateStatus']);
});

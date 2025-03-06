<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TravelOrderController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('check-email', [AuthController::class, 'checkEmail']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('orders', [TravelOrderController::class, 'index']);
    Route::post('orders', [TravelOrderController::class, 'store']);
    Route::get('orders/{id}', [TravelOrderController::class, 'show']);
    Route::put('orders/{id}', [TravelOrderController::class, 'update']);
});

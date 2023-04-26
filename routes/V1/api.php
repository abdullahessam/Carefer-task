<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Booking\ConfirmOrderController;
use App\Http\Controllers\Api\V1\Booking\OrderController;
use App\Http\Controllers\Api\V1\Trip\LineController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['prefix' => 'trips'], function () {
    Route::apiResource('lines', LineController::class)->only(['index', 'show']);
});

Route::group(['prefix' => 'booking', 'middleware' => 'auth:sanctum'], function () {

    Route::apiResource('orders', OrderController::class);

    Route::post('orders/{order}/confirm', ConfirmOrderController::class);
});

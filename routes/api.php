<?php

use App\Http\Controllers\Api\Pos\PosAuthController;
use App\Http\Controllers\Api\Pos\PosBootstrapController;
use App\Http\Controllers\Api\Pos\PosSaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('pos')->group(function () {

    // Public
    Route::post('/login', [PosAuthController::class, 'login']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',    [PosAuthController::class, 'logout']);
        Route::get('/bootstrap',  [PosBootstrapController::class, 'index']);
        Route::post('/sales',     [PosSaleController::class, 'store']);
    });

});

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepotController;
use App\Http\Controllers\Api\DispatchController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ReceiptController;
use App\Http\Controllers\Api\TankController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [
    AuthController::class,
    'register',
]);

Route::post('/login', [
    AuthController::class,
    'login',
]);

Route::middleware('auth:sanctum')
    ->group(function () {

        Route::post('/logout', [
            AuthController::class,
            'logout',
        ]);

        Route::apiResource(
            'depots',
            DepotController::class
        );

        Route::apiResource(
            'tanks',
            TankController::class
        );

        Route::apiResource(
            'receipts',
            ReceiptController::class
        );

        Route::apiResource(
            'dispatches',
            DispatchController::class
        );

        Route::get(
            '/depots/{depot}/inventory',
            [InventoryController::class, 'index']
        );
    });

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

        // Depots

        Route::apiResource(
            'depots',
            DepotController::class
        );


        // Tanks

        Route::apiResource(
            'tanks',
            TankController::class
        );

        // Receipts

        Route::apiResource(
            'receipts',
            ReceiptController::class
        );

        Route::post(
            '/receipts/{receipt}/approve',
            [ReceiptController::class, 'approve']
        )->middleware('depot.manager');

        Route::post(
            '/receipts/{receipt}/reverse',
            [ReceiptController::class, 'reverse']
        );

        // Dispatches

        Route::apiResource(
            'dispatches',
            DispatchController::class
        );

        Route::post(
            '/dispatches/{dispatch}/approve',
            [DispatchController::class, 'approve']
        )->middleware('depot.manager');

        Route::post(
            '/dispatches/{dispatch}/cancel',
            [DispatchController::class, 'cancel']
        );

        // Inventory

        Route::get(
            '/depots/{depot}/inventory',
            [InventoryController::class, 'index']
        );
    });

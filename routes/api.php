<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('customers', CustomerController::class)
            ->parameters(['customers' => 'customer']);

        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart', [CartController::class, 'store']);
        Route::post('/cart/checkout', [CartController::class, 'checkout']);
        Route::patch('/cart/{cart}', [CartController::class, 'update']);
        Route::delete('/cart/{cart}', [CartController::class, 'destroy']);

        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
    });
});

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/categories', [ProductController::class, 'categories']);
});

<?php

use App\Http\Controllers\Api\V1\AdminsController;
use App\Http\Controllers\Api\V1\CurrenciesExchangeController;
use App\Http\Controllers\Api\V1\PaymentsController;
use App\Http\Controllers\Api\v1\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::controller(AdminsController::class)->group(function () {
            Route::post('/login', 'login');
        });
    });
    Route::prefix('user')->group(function () {
        Route::controller(UsersController::class)->group(function () {
            Route::post('/login', 'login');
            Route::post('/create', 'store');
            Route::post('/forgot-password', 'forgotPassword');
            Route::post('/reset-password-token', 'resetPasswordToken');
        });
    });
    Route::controller(CurrenciesExchangeController::class)->group(function () {
        Route::get('/currency/{currency}/{amount}', 'convertCurrency');
    });
    Route::controller(PaymentsController::class)->group(function () {
        Route::post('/payments/create', 'store');
    });
    Route::middleware('auth:api')->group(function () {
        Route::middleware('adminOnly')->group(function () {
            Route::prefix('admin')->group(function () {
                Route::controller(AdminsController::class)->group(function () {
                    Route::post('/create', 'store');
                    Route::get('/user-listing', 'index');
                    Route::put('/user-edit/{uuid}', 'update');
                    Route::delete('/user-delete/{uuid}', 'destroy');
                    Route::get('/logout', 'logout');
                });
            });
        });
        Route::middleware('userOnly')->group(function () {
            Route::prefix('user')->group(function () {
                Route::controller(UsersController::class)->group(function () {
                    Route::get('/', 'show');
                    Route::delete('/', 'destroy');
                    Route::get('/orders', 'orders');
                    Route::put('/edit', 'update');
                    Route::get('/logout', 'logout');
                });
            });
        });
    });
});

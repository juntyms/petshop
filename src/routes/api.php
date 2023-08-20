<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UsersController;
use App\Http\Controllers\Api\V1\AdminsController;

Route::prefix('v1')->group(function () {

    // not Authenticated Routes
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

    //Authenticated Routes
    Route::middleware('auth:api')->group(function () {

        //Admin Middleware
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

        //User Middleware
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

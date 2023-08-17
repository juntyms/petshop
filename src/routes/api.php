<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UsersController;
use App\Http\Controllers\Api\V1\AdminsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function() {

    // not Authenticated Routes
    Route::prefix('admin')->group(function() {
        Route::controller(AdminsController::class)->group(function() {
            Route::post('/login','login');
        });

    });



    //Authenticated Routes
    Route::middleware('auth:api')->group(function() {

        //Admin Middleware
        Route::middleware('adminOnly')->group(function() {
            Route::prefix('admin')->group(function() {
                Route::controller(AdminsController::class)->group(function() {
                    Route::post('/create', 'store');
                    Route::get('/user-listing','index');
                    Route::put('/user-edit/{uuid}','update');
                    Route::delete('/user-delete/{uuid}','destroy');
                    Route::get('/logout','logout');
                });
            });
        });

        //User Middleware
        Route::middleware('userOnly')->group(function() {
            Route::prefix('user')->group(function() {
                Route::controller(UsersController::class)->group(function() {
                    Route::get('user','show');
                    Route::delete('user','destroy');
                    Route::get('orders','orders');
                    Route::put('edit','update');
                    Route::post('login','login');
                    Route::get('logout','logout');
                    Route::post('create','store');
                    Route::post('forgot-password','forgotPassword');
                    Route::post('reset-password-token','resetPasswordToken');
                });
            });
        });
    });


});
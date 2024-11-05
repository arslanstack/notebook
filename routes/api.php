<?php

use App\Http\Controllers\API\User\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/user'
], function ($router) {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/forgot', [AuthController::class, 'forgot'])->name('forgot');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
    Route::post('/updateProfile', [AuthController::class, 'updateProfile'])->middleware('auth:api')->name('updateProfile');
    Route::post('/updatePassword', [AuthController::class, 'updatePassword'])->middleware('auth:api')->name('updatePassword');
    
});

Route::group([
    'middleware' => ['api', 'auth:api'],
], function ($router) {

    

});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('v1')->group(function() {

        Route::prefix('auth')->group(function () {
            Route::post('register', [AuthController::class, 'register'])->name('register');
            Route::post('verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
            Route::post('resend-verification', [AuthController::class, 'resendVerifyEmail'])->name('resend-verify-email');
            Route::post('login', [AuthController::class, 'login'])->name('login');
            Route::post('forgot-password', [ForgetPasswordController::class, 'forgotPassword'])->name('forgot-password');
            Route::post('reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('reset-password');
            Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
            Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum')->name('refresh');
        });

    Route::prefix('user')->as('auth')->group(function () {

    });

});


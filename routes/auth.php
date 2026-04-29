<?php

use LaravelUi5\Auth\Controllers\LoginController;
use LaravelUi5\Auth\Controllers\LogoutController;
use LaravelUi5\Auth\Controllers\ResetPasswordController;

Route::middleware('web')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', LoginController::class)
            ->name('login');

        Route::get('/password/reset/{token}', ResetPasswordController::class)
            ->name('password.reset');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', LogoutController::class)
            ->name('logout');
    });

});

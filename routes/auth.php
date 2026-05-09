<?php

use LaravelUi5\Auth\Controllers\ForgotPasswordController;
use LaravelUi5\Auth\Controllers\LoginController;
use LaravelUi5\Auth\Controllers\LoginRedirectController;
use LaravelUi5\Auth\Controllers\LogoutController;
use LaravelUi5\Auth\Controllers\ResetPasswordController;
use LaravelUi5\Auth\Controllers\ResetPasswordRedirectController;

Route::middleware('web')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', LoginRedirectController::class)
            ->name('login');

        Route::post('/auth/login', LoginController::class)
            ->name('login.submit');

        Route::post('/auth/forgot-password', ForgotPasswordController::class)
            ->name('password.email');

        Route::get('/password/reset/{token}', ResetPasswordRedirectController::class)
            ->name('password.reset');

        Route::post('/auth/reset-password', ResetPasswordController::class)
            ->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', LogoutController::class)
            ->name('logout');
    });

});

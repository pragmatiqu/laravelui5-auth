<?php

use Pragmatiqu\Auth\Controllers\LoginController;
use Pragmatiqu\Auth\Controllers\LogoutController;

Route::middleware('web')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', LoginController::class)
            ->name('login');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', LogoutController::class)
            ->name('logout');
    });

});

<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::any(
    '/soap/server',
    [
        \App\Http\Controllers\Soap\SoapController::class,
        'server'
    ]
)->withoutMiddleware(VerifyCsrfToken::class)
    ->name('soap.server');

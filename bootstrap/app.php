<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // MIDDLEWARE NOTIFIKASI
        $middleware->web(append: [
            \App\Http\Middleware\MarkNotificationAsRead::class,
        ]);

        // MIDDLEWARE CSRF
        $middleware->web(append: [
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

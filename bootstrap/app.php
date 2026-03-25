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
        $middleware->alias([
            'CheckRole' => App\Http\Middleware\CheckRole::class,
            'permission' => App\Http\Middleware\CheckPermission::class,
            'GetGlobalVariable' => App\Http\Middleware\GetGlobalVariable::class,
            'XSSProtection' => App\Http\Middleware\XSSProtection::class,
            'PreventDebugMode' => App\Http\Middleware\PreventDebugMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

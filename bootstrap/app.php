<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Increase PHP limits for large file uploads
ini_set('max_execution_time', '300'); // 5 minutes
ini_set('memory_limit', '256M');

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $appEnv = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null;

        if ($appEnv === 'testing') {
            $middleware->removeFromGroup('web', \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
        }
        
        // Add custom middleware for handling large upload errors
        $middleware->append(\App\Http\Middleware\HandleLargeUploadErrors::class);

        // Register custom middleware aliases
        $middleware->alias([
            'check-manage-access' => \App\Http\Middleware\CheckManageAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

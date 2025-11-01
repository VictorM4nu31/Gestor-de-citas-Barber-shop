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
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'active.barbero' => \App\Http\Middleware\CheckActiveBarbero::class,
            'gallery.rate_limit' => \App\Http\Middleware\GalleryUploadRateLimit::class,
            'gallery.security' => \App\Http\Middleware\GallerySecurityHeaders::class,
            'gallery.image_headers' => \App\Http\Middleware\GalleryImageHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();

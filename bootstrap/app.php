<?php

use App\Http\Middleware\CheckActiveBarbero;
use App\Http\Middleware\GalleryImageHeaders;
use App\Http\Middleware\GallerySecurityHeaders;
use App\Http\Middleware\GalleryUploadRateLimit;
use App\Http\Middleware\LocalizationErrorHandlerMiddleware;
use App\Http\Middleware\LocalizationMiddleware;
use App\Http\Middleware\SanitizeLocaleInputMiddleware;
use App\Http\Middleware\TranslationMetricsMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register localization middleware globally for web routes
        $middleware->web(append: [
            SanitizeLocaleInputMiddleware::class,
            LocalizationMiddleware::class,
            LocalizationErrorHandlerMiddleware::class,
            TranslationMetricsMiddleware::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'active.barbero' => CheckActiveBarbero::class,
            'gallery.rate_limit' => GalleryUploadRateLimit::class,
            'gallery.security' => GallerySecurityHeaders::class,
            'gallery.image_headers' => GalleryImageHeaders::class,
            'localization' => LocalizationMiddleware::class,
            'localization.sanitize' => SanitizeLocaleInputMiddleware::class,
            'localization.error_handler' => LocalizationErrorHandlerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();

<?php

namespace App\Providers;

use App\Console\Commands\TranslationAlertCommand;
use App\Console\Commands\TranslationCacheCommand;
use App\Console\Commands\TranslationMetricsCommand;
use App\Console\Commands\TranslationOptimizeCommand;
use App\Listeners\TranslationMissingListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Events\TranslationMissing;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register translation missing event listener
        Event::listen(TranslationMissing::class, TranslationMissingListener::class);

        // Additional logging in development environment
        if (app()->environment('local', 'development') && config('app.log_missing_translations')) {
            Event::listen(TranslationMissing::class, function (TranslationMissing $event) {
                Log::warning('Missing translation (legacy)', [
                    'key' => $event->key,
                    'locale' => $event->locale,
                    'fallback' => $event->fallback ?? 'none',
                    'namespace' => $event->namespace ?? 'default',
                ]);
            });
        }

        // Register translation management commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                TranslationCacheCommand::class,
                TranslationOptimizeCommand::class,
                TranslationMetricsCommand::class,
                TranslationAlertCommand::class,
            ]);
        }
    }
}

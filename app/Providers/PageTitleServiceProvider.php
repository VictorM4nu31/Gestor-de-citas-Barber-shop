<?php

namespace App\Providers;

use App\Services\PageTitleService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class PageTitleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share page title with all views
        View::composer('*', function ($view) {
            $view->with('pageTitle', PageTitleService::getCurrentTitle());
        });
    }
}

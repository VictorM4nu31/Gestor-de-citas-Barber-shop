<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TranslationMetricsService;
use Symfony\Component\HttpFoundation\Response;

class TranslationMetricsMiddleware
{
    protected TranslationMetricsService $metricsService;

    public function __construct(TranslationMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track language usage after the request is processed
        $this->trackUsage($request);

        return $response;
    }

    /**
     * Track language usage metrics
     */
    protected function trackUsage(Request $request): void
    {
        try {
            // Only track for web routes, not API or admin routes
            if (!$request->is('api/*') && !$request->is('admin/*')) {
                $locale = app()->getLocale();
                $userAgent = $request->userAgent();
                $ipAddress = $request->ip();

                $this->metricsService->trackLanguageUsage($locale, $userAgent, $ipAddress);
            }
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the application
            \Log::error('Translation metrics tracking failed', [
                'error' => $e->getMessage(),
                'request_url' => $request->url(),
            ]);
        }
    }
}
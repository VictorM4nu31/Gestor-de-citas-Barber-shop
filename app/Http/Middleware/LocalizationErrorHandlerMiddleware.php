<?php

namespace App\Http\Middleware;

use App\Services\TranslationMetricsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocalizationErrorHandlerMiddleware
{
    protected TranslationMetricsService $metricsService;

    public function __construct(TranslationMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    /**
     * Handle an incoming request and catch any localization-related errors.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);

            // Check if the current locale is still valid after processing
            $currentLocale = App::getLocale();
            $availableLocales = config('app.available_locales', ['es', 'en']);

            if (! in_array($currentLocale, $availableLocales)) {
                $this->handleInvalidLocale($currentLocale);
            }

            return $response;

        } catch (\Exception $e) {
            // Handle any localization-related exceptions
            return $this->handleLocalizationException($e, $request);
        }
    }

    /**
     * Handle invalid locale scenarios
     */
    private function handleInvalidLocale(string $invalidLocale): void
    {
        $fallbackLocale = config('app.fallback_locale', 'es');

        Log::warning('Invalid locale detected during request processing', [
            'invalid_locale' => $invalidLocale,
            'fallback_locale' => $fallbackLocale,
            'user_agent' => request()->userAgent(),
            'ip' => request()->ip(),
        ]);

        // Track the error in metrics
        $this->metricsService->trackTranslationError(
            "Invalid locale: {$invalidLocale}",
            $invalidLocale,
            [
                'fallback_locale' => $fallbackLocale,
                'user_agent' => request()->userAgent(),
            ]
        );

        // Reset to fallback locale
        App::setLocale($fallbackLocale);
        Session::put('locale', $fallbackLocale);
    }

    /**
     * Handle localization-related exceptions
     */
    private function handleLocalizationException(\Exception $exception, Request $request): Response
    {
        $fallbackLocale = config('app.fallback_locale', 'es');
        $currentLocale = App::getLocale();

        Log::error('Localization exception occurred', [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'request_url' => $request->fullUrl(),
            'locale' => $currentLocale,
            'fallback_locale' => $fallbackLocale,
        ]);

        // Track the error in metrics
        $this->metricsService->trackTranslationError(
            $exception->getMessage(),
            $currentLocale,
            [
                'request_url' => $request->fullUrl(),
                'fallback_locale' => $fallbackLocale,
                'exception_type' => get_class($exception),
            ]
        );

        // Reset to safe state
        App::setLocale($fallbackLocale);
        Session::put('locale', $fallbackLocale);

        // If this is an AJAX request, return JSON error
        if ($request->expectsJson()) {
            return response()->json([
                'error' => __('common.errors.localization_error'),
                'locale' => $fallbackLocale,
            ], 500);
        }

        // For regular requests, redirect to home with error message
        return redirect()->route('home')
            ->with('error', __('common.errors.localization_error'))
            ->with('locale', $fallbackLocale);
    }
}

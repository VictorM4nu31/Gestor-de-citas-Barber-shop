<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SanitizeLocaleInputMiddleware
{
    /**
     * Handle an incoming request to sanitize locale-related input
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize locale parameter if present
        if ($request->has('lang')) {
            $originalLang = $request->get('lang');
            $sanitizedLang = $this->sanitizeLocaleInput($originalLang);

            // Log if sanitization changed the input (potential security issue)
            if ($originalLang !== $sanitizedLang) {
                Log::warning('Locale input sanitized', [
                    'original' => $originalLang,
                    'sanitized' => $sanitizedLang,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                ]);
            }

            // Replace the parameter with sanitized version
            $request->merge(['lang' => $sanitizedLang]);
        }

        // Sanitize any locale-related form inputs
        if ($request->has('locale')) {
            $originalLocale = $request->get('locale');
            $sanitizedLocale = $this->sanitizeLocaleInput($originalLocale);

            if ($originalLocale !== $sanitizedLocale) {
                Log::warning('Locale form input sanitized', [
                    'original' => $originalLocale,
                    'sanitized' => $sanitizedLocale,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            $request->merge(['locale' => $sanitizedLocale]);
        }

        return $next($request);
    }

    /**
     * Sanitize locale input to prevent injection attacks
     *
     * @param  mixed  $input
     */
    private function sanitizeLocaleInput($input): string
    {
        // Convert to string and handle null/empty values
        if (empty($input) || ! is_string($input)) {
            return config('app.fallback_locale', 'es');
        }

        // Remove any HTML tags
        $input = strip_tags($input);

        // Remove any non-alphanumeric characters except underscore and hyphen
        $input = preg_replace('/[^a-zA-Z0-9_-]/', '', $input);

        // Convert to lowercase for consistency
        $input = strtolower($input);

        // Limit length to prevent buffer overflow attacks
        $input = substr($input, 0, 10);

        // If empty after sanitization, use fallback
        if (empty($input)) {
            return config('app.fallback_locale', 'es');
        }

        // Validate against allowed locales
        $availableLocales = config('app.available_locales', ['es', 'en']);
        if (! in_array($input, $availableLocales)) {
            return config('app.fallback_locale', 'es');
        }

        return $input;
    }
}

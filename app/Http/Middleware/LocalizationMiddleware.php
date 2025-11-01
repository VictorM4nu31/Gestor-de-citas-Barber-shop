<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Get available locales from config
            $availableLocales = config('app.available_locales', ['es', 'en']);
            $defaultLocale = config('app.locale', 'es');
            $fallbackLocale = config('app.fallback_locale', 'es');
            
            // Determine locale from:
            // 1. URL parameter (?lang=es)
            // 2. Session storage
            // 3. Default locale
            $locale = $request->get('lang') 
                ?? Session::get('locale') 
                ?? $defaultLocale;
            
            // Validate and sanitize locale input
            $locale = $this->validateAndSanitizeLocale($locale, $availableLocales, $fallbackLocale);
            
            // Set application locale with fallback
            App::setLocale($locale);
            App::setFallbackLocale($fallbackLocale);
            
            // Store locale in session for persistence
            Session::put('locale', $locale);
            
        } catch (\Exception $e) {
            // Log localization errors and use fallback
            Log::error('Localization middleware error', [
                'error' => $e->getMessage(),
                'request_locale' => $request->get('lang'),
                'session_locale' => Session::get('locale')
            ]);
            
            // Set safe fallback locale
            $fallbackLocale = config('app.fallback_locale', 'es');
            App::setLocale($fallbackLocale);
            Session::put('locale', $fallbackLocale);
        }
        
        return $next($request);
    }
    
    /**
     * Validate and sanitize locale input
     *
     * @param string $locale
     * @param array $availableLocales
     * @param string $fallbackLocale
     * @return string
     */
    private function validateAndSanitizeLocale(string $locale, array $availableLocales, string $fallbackLocale): string
    {
        // Remove any non-alphanumeric characters and convert to lowercase
        $locale = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($locale));
        
        // Limit length to prevent potential issues
        $locale = substr($locale, 0, 10);
        
        // Check if locale is in allowed list
        if (!in_array($locale, $availableLocales)) {
            Log::warning('Invalid locale attempted', [
                'attempted_locale' => $locale,
                'available_locales' => $availableLocales,
                'fallback_used' => $fallbackLocale
            ]);
            
            return $fallbackLocale;
        }
        
        return $locale;
    }
}
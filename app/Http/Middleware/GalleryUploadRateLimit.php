<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class GalleryUploadRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Apply rate limiting only to upload requests
        if ($request->isMethod('POST') && $request->hasFile('images')) {
            $key = 'gallery-upload:'.$request->ip();

            // Allow 10 uploads per minute per IP
            $maxAttempts = 10;
            $decayMinutes = 1;

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Demasiados intentos de subida. Intente nuevamente en '.$seconds.' segundos.',
                        'retry_after' => $seconds,
                    ], 429);
                }

                return redirect()->back()
                    ->withErrors(['error' => 'Demasiados intentos de subida. Intente nuevamente en '.$seconds.' segundos.']);
            }

            RateLimiter::hit($key, $decayMinutes * 60);
        }

        return $next($request);
    }
}

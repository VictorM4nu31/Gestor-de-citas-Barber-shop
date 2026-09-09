<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GallerySecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add security headers for gallery-related requests
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Add Content Security Policy for image uploads
        if ($request->is('admin/gallery*')) {
            $isLocal = app()->environment('local');

            if ($isLocal) {
                // Development: More permissive CSP for easier debugging
                $csp = "default-src 'self'; ".
                       "img-src 'self' data: blob: https: http:; ".
                       "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http:; ".
                       "style-src 'self' 'unsafe-inline' https: http:; ".
                       "font-src 'self' data: https: http:; ".
                       "connect-src 'self' ws: wss: https: http:; ".
                       "form-action 'self'; ".
                       "frame-ancestors 'none'; ".
                       "base-uri 'self'; ".
                       "object-src 'none';";
            } else {
                // Production: Strict CSP with only trusted sources
                $csp = "default-src 'self'; ".
                       "img-src 'self' data: blob: https:; ".
                       "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ".
                       "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com https://cdnjs.cloudflare.com; ".
                       "font-src 'self' data: https://fonts.bunny.net https://fonts.gstatic.com https://cdnjs.cloudflare.com; ".
                       "connect-src 'self'; ".
                       "form-action 'self'; ".
                       "frame-ancestors 'none'; ".
                       "base-uri 'self'; ".
                       "object-src 'none';";
            }

            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Add cache control for static gallery images
        if ($request->is('storage/gallery/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
        }

        return $response;
    }
}

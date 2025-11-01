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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
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
            $csp = "default-src 'self'; " .
                   "img-src 'self' data: blob:; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                   "style-src 'self' 'unsafe-inline'; " .
                   "font-src 'self'; " .
                   "connect-src 'self'; " .
                   "form-action 'self'; " .
                   "frame-ancestors 'none';";
            
            $response->headers->set('Content-Security-Policy', $csp);
        }
        
        // Add cache control for static gallery images
        if ($request->is('storage/gallery/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }
        
        return $response;
    }
}
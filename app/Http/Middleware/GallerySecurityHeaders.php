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
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "font-src 'self' https://fonts.bunny.net https://cdnjs.cloudflare.com; " .
                   "connect-src 'self'; " .
                   "form-action 'self'; " .
                   "frame-ancestors 'none';";
            
            // Add Vite development server support
            if (app()->environment('local')) {
                $csp = str_replace(
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net;",
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net http://localhost:5173 http://[::1]:5173;",
                    $csp
                );
                $csp = str_replace(
                    "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdnjs.cloudflare.com;",
                    "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdnjs.cloudflare.com http://localhost:5173 http://[::1]:5173;",
                    $csp
                );
                $csp = str_replace(
                    "connect-src 'self';",
                    "connect-src 'self' ws://localhost:5173 ws://[::1]:5173 http://localhost:5173 http://[::1]:5173;",
                    $csp
                );
            }
            
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
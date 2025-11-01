<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GalleryImageHeaders
{
    /**
     * Handle an incoming request for gallery images.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only apply to gallery image requests
        if ($request->is('storage/gallery/*')) {
            // Security headers for images
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            
            // Cache control for performance
            if (config('gallery.performance.enable_browser_cache', true)) {
                $maxAge = config('gallery.performance.cache_max_age', 31536000);
                $response->headers->set('Cache-Control', "public, max-age={$maxAge}, immutable");
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
            }
            
            // Prevent direct access to sensitive files
            if (preg_match('/\.(php|html|js|css)$/i', $request->path())) {
                abort(403, 'Access denied');
            }
        }
        
        return $response;
    }
}
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gallery Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security and performance settings for the gallery
    | functionality. These settings help protect against malicious uploads
    | and optimize image processing.
    |
    */

    'security' => [
        /*
        |--------------------------------------------------------------------------
        | File Upload Limits
        |--------------------------------------------------------------------------
        */
        'max_file_size' => env('GALLERY_MAX_FILE_SIZE', 5242880), // 5MB in bytes
        'max_files_per_upload' => env('GALLERY_MAX_FILES_PER_UPLOAD', 10),
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png', 
            'image/webp'
        ],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],

        /*
        |--------------------------------------------------------------------------
        | Image Dimension Limits
        |--------------------------------------------------------------------------
        */
        'min_width' => env('GALLERY_MIN_WIDTH', 100),
        'min_height' => env('GALLERY_MIN_HEIGHT', 100),
        'max_width' => env('GALLERY_MAX_WIDTH', 8000),
        'max_height' => env('GALLERY_MAX_HEIGHT', 8000),

        /*
        |--------------------------------------------------------------------------
        | Rate Limiting
        |--------------------------------------------------------------------------
        */
        'upload_rate_limit' => [
            'max_attempts' => env('GALLERY_RATE_LIMIT_ATTEMPTS', 10),
            'decay_minutes' => env('GALLERY_RATE_LIMIT_DECAY', 1),
        ],

        /*
        |--------------------------------------------------------------------------
        | File Permissions
        |--------------------------------------------------------------------------
        */
        'file_permissions' => 0644,
        'directory_permissions' => 0755,

        /*
        |--------------------------------------------------------------------------
        | Content Security
        |--------------------------------------------------------------------------
        */
        'scan_for_threats' => env('GALLERY_SCAN_THREATS', true),
        'suspicious_patterns' => [
            '/<\?php/i',
            '/<\?=/i',
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/eval\(/i',
            '/base64_decode/i'
        ],
    ],

    'performance' => [
        /*
        |--------------------------------------------------------------------------
        | Image Processing Settings
        |--------------------------------------------------------------------------
        */
        'max_image_width' => env('GALLERY_PROCESS_MAX_WIDTH', 1920),
        'max_image_height' => env('GALLERY_PROCESS_MAX_HEIGHT', 1080),
        'jpeg_quality' => env('GALLERY_JPEG_QUALITY', 85),
        'webp_quality' => env('GALLERY_WEBP_QUALITY', 80),
        'png_compression_level' => env('GALLERY_PNG_COMPRESSION', 6),

        /*
        |--------------------------------------------------------------------------
        | Thumbnail Settings
        |--------------------------------------------------------------------------
        */
        'thumbnail_width' => env('GALLERY_THUMB_WIDTH', 300),
        'thumbnail_height' => env('GALLERY_THUMB_HEIGHT', 300),

        /*
        |--------------------------------------------------------------------------
        | Optimization Features
        |--------------------------------------------------------------------------
        */
        'enable_webp_conversion' => env('GALLERY_ENABLE_WEBP', true),
        'enable_image_sharpening' => env('GALLERY_ENABLE_SHARPENING', true),
        'auto_optimize' => env('GALLERY_AUTO_OPTIMIZE', true),

        /*
        |--------------------------------------------------------------------------
        | Cache Settings
        |--------------------------------------------------------------------------
        */
        'cache_max_age' => env('GALLERY_CACHE_MAX_AGE', 31536000), // 1 year
        'enable_browser_cache' => env('GALLERY_ENABLE_BROWSER_CACHE', true),
    ],

    'storage' => [
        /*
        |--------------------------------------------------------------------------
        | Storage Paths
        |--------------------------------------------------------------------------
        */
        'gallery_path' => 'gallery',
        'thumbnail_path' => 'gallery/thumbnails',
        'temp_path' => 'temp/gallery',

        /*
        |--------------------------------------------------------------------------
        | Cleanup Settings
        |--------------------------------------------------------------------------
        */
        'auto_cleanup_orphaned_files' => env('GALLERY_AUTO_CLEANUP', false),
        'cleanup_schedule' => 'daily', // daily, weekly, monthly
    ],

];
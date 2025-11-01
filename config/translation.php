<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Translation Caching Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the caching behavior for translations in your
    | application. This includes cache duration, cache keys, and performance
    | optimizations for production environments.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Cache Translations
    |--------------------------------------------------------------------------
    |
    | This option determines whether translations should be cached for better
    | performance. In production, this should be enabled to reduce file I/O
    | operations when loading translation files.
    |
    */

    'cache_enabled' => env('TRANSLATION_CACHE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    |
    | The duration (in seconds) for which translations should be cached.
    | Set to null for indefinite caching (recommended for production).
    |
    */

    'cache_duration' => env('TRANSLATION_CACHE_DURATION', null),

    /*
    |--------------------------------------------------------------------------
    | Cache Store
    |--------------------------------------------------------------------------
    |
    | The cache store to use for translation caching. If null, the default
    | cache store will be used. For better performance, consider using
    | 'redis' or 'memcached' in production.
    |
    */

    'cache_store' => env('TRANSLATION_CACHE_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix to use for translation cache keys. This helps avoid
    | collisions with other cached data in your application.
    |
    */

    'cache_key_prefix' => env('TRANSLATION_CACHE_PREFIX', 'translations'),

    /*
    |--------------------------------------------------------------------------
    | Preload Translations
    |--------------------------------------------------------------------------
    |
    | When enabled, all translations for the current locale will be preloaded
    | into memory at the start of the request. This can improve performance
    | for applications with many translation calls.
    |
    */

    'preload_enabled' => env('TRANSLATION_PRELOAD_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Cache Warming
    |--------------------------------------------------------------------------
    |
    | Configuration for warming the translation cache. This can be useful
    | for ensuring translations are cached before users access them.
    |
    */

    'warm_cache' => [
        'enabled' => env('TRANSLATION_WARM_CACHE', true),
        'locales' => ['es', 'en'], // Locales to warm
        'groups' => ['*'], // Translation groups to warm (* for all)
    ],

];
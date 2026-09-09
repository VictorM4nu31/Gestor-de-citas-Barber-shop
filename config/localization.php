<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Localization Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration for the localization
    | system, including rate limiting, input validation, and error handling.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for language switching to prevent abuse.
    |
    */
    'rate_limiting' => [
        'enabled' => env('LOCALIZATION_RATE_LIMITING', true),
        'max_attempts' => env('LOCALIZATION_MAX_ATTEMPTS', 10),
        'decay_minutes' => env('LOCALIZATION_DECAY_MINUTES', 1),
        'key_prefix' => 'language-switch',
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation
    |--------------------------------------------------------------------------
    |
    | Security settings for validating and sanitizing locale input.
    |
    */
    'validation' => [
        'max_length' => 10,
        'allowed_pattern' => '/^[a-z]{2}(_[A-Z]{2})?$/',
        'sanitize_html' => true,
        'log_sanitization' => env('LOG_LOCALE_SANITIZATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    |
    | Configuration for handling localization errors and fallbacks.
    |
    */
    'error_handling' => [
        'log_errors' => env('LOG_LOCALIZATION_ERRORS', true),
        'log_missing_translations' => env('LOG_MISSING_TRANSLATIONS', true),
        'strict_validation' => env('STRICT_LOCALE_VALIDATION', true),
        'fallback_on_error' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Additional security measures for localization endpoints.
    |
    */
    'security' => [
        'csrf_protection' => true,
        'validate_referer' => env('VALIDATE_LOCALE_REFERER', true),
        'allowed_origins' => [
            // Add allowed origins for AJAX requests if needed
        ],
        'block_suspicious_patterns' => [
            'javascript:',
            'data:',
            'vbscript:',
            '<script',
            'eval(',
            'expression(',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring
    |--------------------------------------------------------------------------
    |
    | Settings for monitoring localization usage and security events.
    |
    */
    'monitoring' => [
        'track_usage' => env('TRACK_LOCALE_USAGE', false),
        'alert_on_suspicious_activity' => env('ALERT_LOCALE_SUSPICIOUS', true),
        'max_failed_attempts_before_alert' => 5,
    ],
];

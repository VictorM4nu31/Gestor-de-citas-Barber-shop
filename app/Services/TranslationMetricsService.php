<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TranslationMetricsService
{
    protected string $cachePrefix = 'translation_metrics';

    protected int $cacheDuration = 3600; // 1 hour

    /**
     * Track language usage
     */
    public function trackLanguageUsage(string $locale, ?string $userAgent = null, ?string $ipAddress = null): void
    {
        try {
            // Daily usage tracking
            $dailyKey = $this->getCacheKey('daily_usage', $locale, now()->format('Y-m-d'));
            $dailyCount = Cache::get($dailyKey, 0) + 1;
            Cache::put($dailyKey, $dailyCount, $this->cacheDuration * 24); // 24 hours

            // Hourly usage tracking
            $hourlyKey = $this->getCacheKey('hourly_usage', $locale, now()->format('Y-m-d-H'));
            $hourlyCount = Cache::get($hourlyKey, 0) + 1;
            Cache::put($hourlyKey, $hourlyCount, $this->cacheDuration);

            // Total usage tracking
            $totalKey = $this->getCacheKey('total_usage', $locale);
            $totalCount = Cache::get($totalKey, 0) + 1;
            Cache::put($totalKey, $totalCount, null); // No expiration for total

            // Track unique sessions (simplified)
            if ($ipAddress) {
                $sessionKey = $this->getCacheKey('session', $locale, md5($ipAddress.now()->format('Y-m-d')));
                if (! Cache::has($sessionKey)) {
                    Cache::put($sessionKey, true, $this->cacheDuration * 24);

                    $uniqueKey = $this->getCacheKey('unique_daily', $locale, now()->format('Y-m-d'));
                    $uniqueCount = Cache::get($uniqueKey, 0) + 1;
                    Cache::put($uniqueKey, $uniqueCount, $this->cacheDuration * 24);
                }
            }

            // Log detailed usage for analysis
            Log::channel('single')->info('Language usage tracked', [
                'locale' => $locale,
                'timestamp' => now()->toISOString(),
                'user_agent' => $userAgent,
                'ip_address' => $ipAddress ? hash('sha256', $ipAddress) : null, // Hash IP for privacy
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to track language usage', [
                'locale' => $locale,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Track missing translation
     */
    public function trackMissingTranslation(string $key, string $locale, ?string $group = null): void
    {
        try {
            $missingKey = $this->getCacheKey('missing', $locale, $group ?? 'default');
            $missing = Cache::get($missingKey, []);

            if (! in_array($key, $missing)) {
                $missing[] = $key;
                Cache::put($missingKey, $missing, $this->cacheDuration * 24);
            }

            // Log missing translation
            Log::warning('Missing translation detected', [
                'key' => $key,
                'locale' => $locale,
                'group' => $group,
                'timestamp' => now()->toISOString(),
            ]);

            // Track missing translation count
            $countKey = $this->getCacheKey('missing_count', $locale, now()->format('Y-m-d'));
            $missingCount = Cache::get($countKey, 0) + 1;
            Cache::put($countKey, $missingCount, $this->cacheDuration * 24);

        } catch (\Exception $e) {
            Log::error('Failed to track missing translation', [
                'key' => $key,
                'locale' => $locale,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Track translation error
     */
    public function trackTranslationError(string $error, string $locale, array $context = []): void
    {
        try {
            // Track error count
            $errorKey = $this->getCacheKey('errors', $locale, now()->format('Y-m-d'));
            $errorCount = Cache::get($errorKey, 0) + 1;
            Cache::put($errorKey, $errorCount, $this->cacheDuration * 24);

            // Log error with context
            Log::error('Translation system error', array_merge([
                'error' => $error,
                'locale' => $locale,
                'timestamp' => now()->toISOString(),
            ], $context));

        } catch (\Exception $e) {
            Log::critical('Failed to track translation error', [
                'original_error' => $error,
                'tracking_error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get language usage statistics
     */
    public function getUsageStatistics(?string $locale = null, ?string $period = 'daily'): array
    {
        try {
            $locales = $locale ? [$locale] : config('app.available_locales', ['es', 'en']);
            $stats = [];

            foreach ($locales as $loc) {
                $stats[$loc] = [
                    'total_usage' => Cache::get($this->getCacheKey('total_usage', $loc), 0),
                    'daily_usage' => $this->getPeriodUsage($loc, 'daily'),
                    'hourly_usage' => $this->getPeriodUsage($loc, 'hourly'),
                    'unique_daily' => Cache::get($this->getCacheKey('unique_daily', $loc, now()->format('Y-m-d')), 0),
                    'missing_translations' => $this->getMissingTranslations($loc),
                    'error_count' => Cache::get($this->getCacheKey('errors', $loc, now()->format('Y-m-d')), 0),
                ];
            }

            return $stats;

        } catch (\Exception $e) {
            Log::error('Failed to get usage statistics', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get missing translations for a locale
     */
    public function getMissingTranslations(string $locale): array
    {
        $missing = [];
        $groups = ['common', 'welcome', 'services', 'barberos', 'gallery', 'contact', 'auth'];

        foreach ($groups as $group) {
            $missingKey = $this->getCacheKey('missing', $locale, $group);
            $groupMissing = Cache::get($missingKey, []);

            if (! empty($groupMissing)) {
                $missing[$group] = $groupMissing;
            }
        }

        return $missing;
    }

    /**
     * Clear metrics cache
     */
    public function clearMetrics(?string $locale = null): void
    {
        try {
            $locales = $locale ? [$locale] : config('app.available_locales', ['es', 'en']);

            foreach ($locales as $loc) {
                // Clear various metric types
                $patterns = [
                    'daily_usage', 'hourly_usage', 'total_usage',
                    'unique_daily', 'missing', 'errors',
                ];

                foreach ($patterns as $pattern) {
                    $key = $this->getCacheKey($pattern, $loc);
                    Cache::forget($key);

                    // Clear dated keys (last 7 days)
                    for ($i = 0; $i < 7; $i++) {
                        $date = now()->subDays($i)->format('Y-m-d');
                        $datedKey = $this->getCacheKey($pattern, $loc, $date);
                        Cache::forget($datedKey);

                        // Clear hourly keys for today
                        if ($i === 0) {
                            for ($h = 0; $h < 24; $h++) {
                                $hourKey = $this->getCacheKey($pattern, $loc, $date.'-'.str_pad($h, 2, '0', STR_PAD_LEFT));
                                Cache::forget($hourKey);
                            }
                        }
                    }
                }
            }

            Log::info('Translation metrics cleared', ['locale' => $locale]);

        } catch (\Exception $e) {
            Log::error('Failed to clear metrics', [
                'locale' => $locale,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate cache key
     */
    protected function getCacheKey(string $type, string $locale, ?string $suffix = null): string
    {
        $key = "{$this->cachePrefix}.{$type}.{$locale}";

        if ($suffix) {
            $key .= ".{$suffix}";
        }

        return $key;
    }

    /**
     * Get usage for a specific period
     */
    protected function getPeriodUsage(string $locale, string $period): array
    {
        $usage = [];

        if ($period === 'daily') {
            // Get last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $key = $this->getCacheKey('daily_usage', $locale, $date);
                $usage[$date] = Cache::get($key, 0);
            }
        } elseif ($period === 'hourly') {
            // Get last 24 hours
            for ($i = 23; $i >= 0; $i--) {
                $hour = now()->subHours($i)->format('Y-m-d-H');
                $key = $this->getCacheKey('hourly_usage', $locale, $hour);
                $usage[$hour] = Cache::get($key, 0);
            }
        }

        return $usage;
    }
}

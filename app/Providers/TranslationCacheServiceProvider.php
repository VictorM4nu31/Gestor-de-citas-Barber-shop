<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;

class TranslationCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Override the translation loader to add caching
        $this->app->singleton('translation.loader', function ($app) {
            return new CachedFileLoader($app['files'], $app['path.lang']);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Preload translations if enabled
        if (config('translation.preload_enabled', false)) {
            $this->preloadTranslations();
        }
    }

    /**
     * Preload translations for the current locale
     */
    protected function preloadTranslations(): void
    {
        $locale = app()->getLocale();
        $cacheKey = config('translation.cache_key_prefix', 'translations') . ".preload.{$locale}";
        
        if (!Cache::has($cacheKey)) {
            $translations = $this->loadAllTranslations($locale);
            
            Cache::put(
                $cacheKey,
                $translations,
                config('translation.cache_duration')
            );
        }
    }

    /**
     * Load all translations for a given locale
     */
    protected function loadAllTranslations(string $locale): array
    {
        $translations = [];
        $langPath = resource_path("lang/{$locale}");
        
        if (File::exists($langPath)) {
            $files = File::files($langPath);
            
            foreach ($files as $file) {
                $group = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $translations[$group] = require $file->getPathname();
            }
        }
        
        return $translations;
    }
}

/**
 * Cached file loader for translations
 */
class CachedFileLoader extends FileLoader
{
    /**
     * Load the messages for the given locale.
     */
    public function load($locale, $group, $namespace = null): array
    {
        if (!config('translation.cache_enabled', true)) {
            return parent::load($locale, $group, $namespace);
        }

        $cacheKey = $this->getCacheKey($locale, $group, $namespace);
        
        return Cache::store(config('translation.cache_store'))
            ->remember($cacheKey, config('translation.cache_duration'), function () use ($locale, $group, $namespace) {
                return parent::load($locale, $group, $namespace);
            });
    }

    /**
     * Generate cache key for translation
     */
    protected function getCacheKey(string $locale, string $group, ?string $namespace = null): string
    {
        $prefix = config('translation.cache_key_prefix', 'translations');
        $key = "{$prefix}.{$locale}.{$group}";
        
        if ($namespace) {
            $key .= ".{$namespace}";
        }
        
        return $key;
    }
}
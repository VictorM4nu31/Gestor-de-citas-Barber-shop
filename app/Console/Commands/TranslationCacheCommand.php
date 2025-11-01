<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class TranslationCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translation:cache 
                            {--clear : Clear the translation cache instead of warming it}
                            {--locale= : Cache translations for specific locale only}';

    /**
     * The console command description.
     */
    protected $description = 'Cache or clear translation files for better performance';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('clear')) {
            return $this->clearCache();
        }

        return $this->warmCache();
    }

    /**
     * Warm the translation cache
     */
    protected function warmCache(): int
    {
        $this->info('Warming translation cache...');
        
        $locales = $this->option('locale') 
            ? [$this->option('locale')] 
            : config('translation.warm_cache.locales', config('app.available_locales', ['es', 'en']));

        $cachedCount = 0;

        foreach ($locales as $locale) {
            $this->line("Caching translations for locale: {$locale}");
            
            $langPath = resource_path("lang/{$locale}");
            
            if (!File::exists($langPath)) {
                $this->warn("Language directory not found: {$langPath}");
                continue;
            }

            $files = File::files($langPath);
            
            foreach ($files as $file) {
                $group = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $translations = require $file->getPathname();
                
                $cacheKey = $this->getCacheKey($locale, $group);
                
                Cache::store(config('translation.cache_store'))
                    ->put($cacheKey, $translations, config('translation.cache_duration'));
                
                $cachedCount++;
                $this->line("  - Cached {$group}.php");
            }

            // Cache preloaded translations if enabled
            if (config('translation.preload_enabled', false)) {
                $preloadKey = config('translation.cache_key_prefix', 'translations') . ".preload.{$locale}";
                $allTranslations = $this->loadAllTranslations($locale);
                
                Cache::store(config('translation.cache_store'))
                    ->put($preloadKey, $allTranslations, config('translation.cache_duration'));
                
                $this->line("  - Cached preload data");
            }
        }

        $this->info("Successfully cached {$cachedCount} translation files.");
        
        return self::SUCCESS;
    }

    /**
     * Clear the translation cache
     */
    protected function clearCache(): int
    {
        $this->info('Clearing translation cache...');
        
        $prefix = config('translation.cache_key_prefix', 'translations');
        $store = Cache::store(config('translation.cache_store'));
        
        // Clear individual translation caches
        $locales = config('app.available_locales', ['es', 'en']);
        $clearedCount = 0;
        
        foreach ($locales as $locale) {
            $langPath = resource_path("lang/{$locale}");
            
            if (File::exists($langPath)) {
                $files = File::files($langPath);
                
                foreach ($files as $file) {
                    $group = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $cacheKey = $this->getCacheKey($locale, $group);
                    
                    if ($store->forget($cacheKey)) {
                        $clearedCount++;
                    }
                }
                
                // Clear preload cache
                $preloadKey = "{$prefix}.preload.{$locale}";
                $store->forget($preloadKey);
            }
        }
        
        $this->info("Successfully cleared {$clearedCount} cached translation files.");
        
        return self::SUCCESS;
    }

    /**
     * Generate cache key for translation
     */
    protected function getCacheKey(string $locale, string $group): string
    {
        $prefix = config('translation.cache_key_prefix', 'translations');
        return "{$prefix}.{$locale}.{$group}";
    }

    /**
     * Load all translations for a locale
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
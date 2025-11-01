<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class TranslationOptimizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translation:optimize 
                            {--validate : Validate translation files for missing keys}
                            {--minify : Minify translation files by removing comments and extra whitespace}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize translation files for production deployment';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Optimizing translations for production...');

        // Validate translations if requested
        if ($this->option('validate')) {
            $this->validateTranslations();
        }

        // Minify translation files if requested
        if ($this->option('minify')) {
            $this->minifyTranslations();
        }

        // Cache translations
        $this->info('Caching translations...');
        Artisan::call('translation:cache');
        $this->line(Artisan::output());

        // Cache Laravel configuration
        $this->info('Caching configuration...');
        Artisan::call('config:cache');

        $this->info('Translation optimization completed successfully!');
        
        return self::SUCCESS;
    }

    /**
     * Validate translation files for consistency
     */
    protected function validateTranslations(): void
    {
        $this->info('Validating translation files...');
        
        $locales = config('app.available_locales', ['es', 'en']);
        $baseLocale = config('app.locale', 'es');
        
        $issues = [];
        
        // Get all translation files from base locale
        $basePath = resource_path("lang/{$baseLocale}");
        if (!File::exists($basePath)) {
            $this->error("Base locale directory not found: {$basePath}");
            return;
        }
        
        $baseFiles = File::files($basePath);
        
        foreach ($baseFiles as $file) {
            $group = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $baseTranslations = require $file->getPathname();
            $baseKeys = $this->flattenArray($baseTranslations);
            
            // Check other locales
            foreach ($locales as $locale) {
                if ($locale === $baseLocale) continue;
                
                $localePath = resource_path("lang/{$locale}/{$group}.php");
                
                if (!File::exists($localePath)) {
                    $issues[] = "Missing translation file: {$locale}/{$group}.php";
                    continue;
                }
                
                $localeTranslations = require $localePath;
                $localeKeys = $this->flattenArray($localeTranslations);
                
                // Check for missing keys
                $missingKeys = array_diff_key($baseKeys, $localeKeys);
                foreach ($missingKeys as $key => $value) {
                    $issues[] = "Missing key '{$key}' in {$locale}/{$group}.php";
                }
                
                // Check for extra keys
                $extraKeys = array_diff_key($localeKeys, $baseKeys);
                foreach ($extraKeys as $key => $value) {
                    $issues[] = "Extra key '{$key}' in {$locale}/{$group}.php";
                }
            }
        }
        
        if (empty($issues)) {
            $this->info('✓ All translation files are valid and consistent.');
        } else {
            $this->warn('Found ' . count($issues) . ' translation issues:');
            foreach ($issues as $issue) {
                $this->line("  - {$issue}");
            }
        }
    }

    /**
     * Minify translation files by removing comments and extra whitespace
     */
    protected function minifyTranslations(): void
    {
        $this->info('Minifying translation files...');
        
        $locales = config('app.available_locales', ['es', 'en']);
        $minifiedCount = 0;
        
        foreach ($locales as $locale) {
            $langPath = resource_path("lang/{$locale}");
            
            if (!File::exists($langPath)) {
                continue;
            }
            
            $files = File::files($langPath);
            
            foreach ($files as $file) {
                $content = File::get($file->getPathname());
                
                // Remove PHP comments (// and /* */)
                $content = preg_replace('/\/\*[\s\S]*?\*\//', '', $content);
                $content = preg_replace('/\/\/.*$/m', '', $content);
                
                // Remove extra whitespace while preserving structure
                $content = preg_replace('/\n\s*\n/', "\n", $content);
                $content = preg_replace('/\s+$/', '', $content);
                
                File::put($file->getPathname(), $content);
                $minifiedCount++;
            }
        }
        
        $this->info("✓ Minified {$minifiedCount} translation files.");
    }

    /**
     * Flatten a multi-dimensional array with dot notation
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        
        return $result;
    }
}
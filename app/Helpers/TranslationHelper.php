<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class TranslationHelper
{
    /**
     * Get translated attribute from a model
     * 
     * @param mixed $model The model instance
     * @param string $attribute The base attribute name
     * @param string|null $locale The locale to use (defaults to current locale)
     * @return string The translated value or fallback
     */
    public static function getTranslatedAttribute($model, string $attribute, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        $fallbackLocale = config('app.fallback_locale', 'es');
        
        // Try to get the localized attribute
        $localizedAttribute = "{$attribute}_{$locale}";
        
        if (isset($model->$localizedAttribute) && !empty($model->$localizedAttribute)) {
            return $model->$localizedAttribute;
        }
        
        // Try fallback locale if different from current
        if ($locale !== $fallbackLocale) {
            $fallbackAttribute = "{$attribute}_{$fallbackLocale}";
            if (isset($model->$fallbackAttribute) && !empty($model->$fallbackAttribute)) {
                return $model->$fallbackAttribute;
            }
        }
        
        // Return original attribute as last resort
        return $model->$attribute ?? '';
    }

    /**
     * Get all available locales
     * 
     * @return array
     */
    public static function getAvailableLocales(): array
    {
        return ['es', 'en'];
    }

    /**
     * Check if a locale is supported
     * 
     * @param string $locale
     * @return bool
     */
    public static function isLocaleSupported(string $locale): bool
    {
        return in_array($locale, self::getAvailableLocales());
    }

    /**
     * Get translation key with fallback
     * 
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public static function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        
        // Try to get translation in requested locale
        $translation = trans($key, $replace, $locale);
        
        // If translation is the same as key (not found), try fallback
        if ($translation === $key && $locale !== config('app.fallback_locale')) {
            $translation = trans($key, $replace, config('app.fallback_locale'));
        }
        
        return $translation;
    }
}
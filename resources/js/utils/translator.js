/**
 * JavaScript Translation System
 * Provides client-side translation functionality similar to Laravel's __() helper
 */

class Translator {
    constructor() {
        this.locale = document.documentElement.lang || 'es';
        this.translations = {};
        this.fallbackLocale = 'es';
        this.loadTranslations();
    }

    /**
     * Load translations from the server or embedded data
     */
    loadTranslations() {
        // Try to get translations from embedded script tag
        const translationScript = document.querySelector('script[data-translations]');
        if (translationScript) {
            try {
                this.translations = JSON.parse(translationScript.textContent);
            } catch (e) {
                console.warn('Failed to parse embedded translations:', e);
            }
        }

        // If no embedded translations, try to load from window object
        if (Object.keys(this.translations).length === 0 && window.translations) {
            this.translations = window.translations;
        }
    }

    /**
     * Get translation for a key
     * @param {string} key - Translation key (e.g., 'gallery.admin.upload.title')
     * @param {object} replacements - Object with replacement values
     * @param {string} locale - Specific locale to use
     * @returns {string} Translated text
     */
    get(key, replacements = {}, locale = null) {
        const targetLocale = locale || this.locale;
        let translation = this.getTranslationFromKey(key, targetLocale);

        // Fallback to default locale if translation not found
        if (translation === key && targetLocale !== this.fallbackLocale) {
            translation = this.getTranslationFromKey(key, this.fallbackLocale);
        }

        // Apply replacements
        if (typeof translation === 'string' && Object.keys(replacements).length > 0) {
            translation = this.applyReplacements(translation, replacements);
        }

        return translation;
    }

    /**
     * Get translation from nested key
     * @param {string} key - Dot notation key
     * @param {string} locale - Locale to use
     * @returns {string} Translation or original key if not found
     */
    getTranslationFromKey(key, locale) {
        const localeTranslations = this.translations[locale] || {};
        const keys = key.split('.');
        let current = localeTranslations;

        for (const keyPart of keys) {
            if (current && typeof current === 'object' && keyPart in current) {
                current = current[keyPart];
            } else {
                return key; // Return original key if not found
            }
        }

        return typeof current === 'string' ? current : key;
    }

    /**
     * Apply replacements to translation string
     * @param {string} translation - Translation string with placeholders
     * @param {object} replacements - Replacement values
     * @returns {string} Translation with replacements applied
     */
    applyReplacements(translation, replacements) {
        let result = translation;

        Object.keys(replacements).forEach(key => {
            const placeholder = `:${key}`;
            const value = replacements[key];
            result = result.replace(new RegExp(placeholder, 'g'), value);
        });

        return result;
    }

    /**
     * Set the current locale
     * @param {string} locale - New locale
     */
    setLocale(locale) {
        this.locale = locale;
    }

    /**
     * Get the current locale
     * @returns {string} Current locale
     */
    getLocale() {
        return this.locale;
    }

    /**
     * Check if a translation exists
     * @param {string} key - Translation key
     * @param {string} locale - Locale to check
     * @returns {boolean} True if translation exists
     */
    has(key, locale = null) {
        const targetLocale = locale || this.locale;
        const translation = this.getTranslationFromKey(key, targetLocale);
        return translation !== key;
    }

    /**
     * Add translations dynamically
     * @param {string} locale - Locale
     * @param {object} translations - Translation object
     */
    addTranslations(locale, translations) {
        if (!this.translations[locale]) {
            this.translations[locale] = {};
        }
        
        this.translations[locale] = this.mergeDeep(this.translations[locale], translations);
    }

    /**
     * Deep merge objects
     * @param {object} target - Target object
     * @param {object} source - Source object
     * @returns {object} Merged object
     */
    mergeDeep(target, source) {
        const result = { ...target };
        
        Object.keys(source).forEach(key => {
            if (source[key] && typeof source[key] === 'object' && !Array.isArray(source[key])) {
                result[key] = this.mergeDeep(result[key] || {}, source[key]);
            } else {
                result[key] = source[key];
            }
        });
        
        return result;
    }

    /**
     * Get all translations for debugging
     * @returns {object} All translations
     */
    getAllTranslations() {
        return this.translations;
    }
}

// Create global instance
const translator = new Translator();

// Global translation function (similar to Laravel's __() helper)
window.__ = function(key, replacements = {}, locale = null) {
    return translator.get(key, replacements, locale);
};

// Export translator instance
window.translator = translator;

// Export class for manual instantiation
window.Translator = Translator;

export default translator;
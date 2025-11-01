<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class LanguageSwitchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxLength = config('localization.validation.max_length', 10);
        $pattern = config('localization.validation.allowed_pattern', '/^[a-z]{2}(_[A-Z]{2})?$/');
        
        return [
            'locale' => [
                'required',
                'string',
                "max:{$maxLength}",
                "regex:{$pattern}",
                Rule::in(config('app.available_locales', ['es', 'en']))
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'locale.required' => __('common.errors.invalid_locale'),
            'locale.string' => __('common.errors.invalid_locale'),
            'locale.max' => __('common.errors.invalid_locale'),
            'locale.regex' => __('common.errors.invalid_locale'),
            'locale.in' => __('common.errors.invalid_locale'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Get locale from route parameter
        $locale = $this->route('locale');
        
        if ($locale) {
            // Remove any potentially dangerous characters
            $locale = preg_replace('/[^a-zA-Z0-9_-]/', '', $locale);
            
            // Convert to lowercase for consistency
            $locale = strtolower($locale);
            
            // Limit length
            $locale = substr($locale, 0, 10);
            
            // Merge the sanitized locale into the request data for validation
            $this->merge(['locale' => $locale]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Apply rate limiting
        $this->applyRateLimit();
    }

    /**
     * Apply rate limiting to language switch requests
     *
     * @throws ThrottleRequestsException
     */
    private function applyRateLimit(): void
    {
        if (!config('localization.rate_limiting.enabled', true)) {
            return;
        }

        $keyPrefix = config('localization.rate_limiting.key_prefix', 'language-switch');
        $key = $keyPrefix . ':' . $this->ip();
        $maxAttempts = config('localization.rate_limiting.max_attempts', 10);
        $decayMinutes = config('localization.rate_limiting.decay_minutes', 1);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw new ThrottleRequestsException(
                __('common.errors.too_many_language_switches', ['seconds' => $seconds]),
                null,
                [],
                $seconds
            );
        }

        RateLimiter::hit($key, $decayMinutes * 60);
    }

    /**
     * Get the validated locale
     */
    public function getValidatedLocale(): string
    {
        return $this->validated()['locale'] ?? $this->route('locale');
    }
}
<?php

namespace App\Listeners;

use App\Services\TranslationMetricsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\Events\TranslationMissing;

class TranslationMissingListener
{
    protected TranslationMetricsService $metricsService;

    public function __construct(TranslationMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    /**
     * Handle the event.
     */
    public function handle(TranslationMissing $event): void
    {
        try {
            // Track the missing translation
            $this->metricsService->trackMissingTranslation(
                $event->key,
                $event->locale,
                $event->group
            );

            // Log additional context if in development
            if (app()->environment('local', 'development')) {
                Log::channel('single')->warning('Missing translation key', [
                    'key' => $event->key,
                    'locale' => $event->locale,
                    'group' => $event->group,
                    'namespace' => $event->namespace,
                    'replacements' => $event->replace,
                    'stack_trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to handle missing translation event', [
                'event_key' => $event->key,
                'event_locale' => $event->locale,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

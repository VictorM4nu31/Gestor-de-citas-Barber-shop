<?php

namespace App\Http\Controllers;

use App\Services\TranslationMetricsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationMetricsController extends Controller
{
    protected TranslationMetricsService $metricsService;

    public function __construct(TranslationMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    /**
     * Get translation metrics as JSON (for admin dashboard or monitoring)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $locale = $request->query('locale');
            $stats = $this->metricsService->getUsageStatistics($locale);

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve metrics',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get missing translations summary
     */
    public function missing(Request $request): JsonResponse
    {
        try {
            $locale = $request->query('locale');
            $locales = $locale ? [$locale] : config('app.available_locales', ['es', 'en']);

            $missing = [];
            foreach ($locales as $loc) {
                $missing[$loc] = $this->metricsService->getMissingTranslations($loc);
            }

            return response()->json([
                'success' => true,
                'data' => $missing,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve missing translations',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear metrics (admin only)
     */
    public function clear(Request $request): JsonResponse
    {
        try {
            $locale = $request->input('locale');
            $this->metricsService->clearMetrics($locale);

            return response()->json([
                'success' => true,
                'message' => 'Metrics cleared successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to clear metrics',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

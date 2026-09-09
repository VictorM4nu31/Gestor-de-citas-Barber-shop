<?php

namespace App\Console\Commands;

use App\Services\TranslationMetricsService;
use Illuminate\Console\Command;

class TranslationMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translation:metrics 
                            {--locale= : Show metrics for specific locale only}
                            {--clear : Clear all metrics data}
                            {--export= : Export metrics to file (json|csv)}';

    /**
     * The console command description.
     */
    protected $description = 'View or manage translation usage metrics and statistics';

    protected TranslationMetricsService $metricsService;

    public function __construct(TranslationMetricsService $metricsService)
    {
        parent::__construct();
        $this->metricsService = $metricsService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('clear')) {
            return $this->clearMetrics();
        }

        if ($this->option('export')) {
            return $this->exportMetrics();
        }

        return $this->showMetrics();
    }

    /**
     * Show translation metrics
     */
    protected function showMetrics(): int
    {
        $locale = $this->option('locale');
        $stats = $this->metricsService->getUsageStatistics($locale);

        if (empty($stats)) {
            $this->info('No metrics data available.');

            return self::SUCCESS;
        }

        $this->info('Translation Usage Metrics');
        $this->line('==========================');

        foreach ($stats as $loc => $data) {
            $this->newLine();
            $this->line("Locale: <info>{$loc}</info>");
            $this->line(str_repeat('-', 20));

            $this->line("Total Usage: <comment>{$data['total_usage']}</comment>");
            $this->line("Unique Daily Users: <comment>{$data['unique_daily']}</comment>");
            $this->line("Daily Errors: <comment>{$data['error_count']}</comment>");

            // Show daily usage trend
            if (! empty($data['daily_usage'])) {
                $this->line("\nDaily Usage (Last 7 days):");
                foreach ($data['daily_usage'] as $date => $count) {
                    $this->line("  {$date}: {$count}");
                }
            }

            // Show missing translations
            if (! empty($data['missing_translations'])) {
                $this->line("\n<fg=yellow>Missing Translations:</>");
                foreach ($data['missing_translations'] as $group => $keys) {
                    $this->line("  <fg=red>{$group}:</> ".count($keys).' missing keys');
                    if ($this->output->isVerbose()) {
                        foreach ($keys as $key) {
                            $this->line("    - {$key}");
                        }
                    }
                }

                if (! $this->output->isVerbose()) {
                    $this->line('  <fg=gray>Use -v flag to see detailed missing keys</>');
                }
            }
        }

        return self::SUCCESS;
    }

    /**
     * Clear metrics data
     */
    protected function clearMetrics(): int
    {
        $locale = $this->option('locale');

        if ($this->confirm('Are you sure you want to clear translation metrics'.($locale ? " for locale '{$locale}'" : '').'?')) {
            $this->metricsService->clearMetrics($locale);
            $this->info('Translation metrics cleared successfully.');
        } else {
            $this->info('Operation cancelled.');
        }

        return self::SUCCESS;
    }

    /**
     * Export metrics to file
     */
    protected function exportMetrics(): int
    {
        $format = $this->option('export');
        $locale = $this->option('locale');

        if (! in_array($format, ['json', 'csv'])) {
            $this->error('Invalid export format. Use json or csv.');

            return self::FAILURE;
        }

        $stats = $this->metricsService->getUsageStatistics($locale);

        if (empty($stats)) {
            $this->error('No metrics data to export.');

            return self::FAILURE;
        }

        $filename = 'translation_metrics_'.now()->format('Y-m-d_H-i-s').'.'.$format;
        $filepath = storage_path("app/{$filename}");

        try {
            if ($format === 'json') {
                file_put_contents($filepath, json_encode($stats, JSON_PRETTY_PRINT));
            } else {
                $this->exportToCsv($stats, $filepath);
            }

            $this->info("Metrics exported to: {$filepath}");

        } catch (\Exception $e) {
            $this->error("Failed to export metrics: {$e->getMessage()}");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Export metrics to CSV format
     */
    protected function exportToCsv(array $stats, string $filepath): void
    {
        $handle = fopen($filepath, 'w');

        // Write header
        fputcsv($handle, [
            'Locale', 'Total Usage', 'Unique Daily', 'Error Count',
            'Missing Translations', 'Date', 'Daily Usage',
        ]);

        foreach ($stats as $locale => $data) {
            $missingCount = array_sum(array_map('count', $data['missing_translations']));

            // Write summary row
            fputcsv($handle, [
                $locale,
                $data['total_usage'],
                $data['unique_daily'],
                $data['error_count'],
                $missingCount,
                '',
                '',
            ]);

            // Write daily usage details
            foreach ($data['daily_usage'] as $date => $count) {
                fputcsv($handle, [
                    $locale,
                    '',
                    '',
                    '',
                    '',
                    $date,
                    $count,
                ]);
            }
        }

        fclose($handle);
    }
}

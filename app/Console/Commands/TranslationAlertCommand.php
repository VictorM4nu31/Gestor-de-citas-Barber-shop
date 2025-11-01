<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslationMetricsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TranslationAlertCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translation:alert 
                            {--threshold=5 : Minimum number of missing translations to trigger alert}
                            {--email= : Email address to send alerts to}
                            {--dry-run : Show what would be alerted without sending}';

    /**
     * The console command description.
     */
    protected $description = 'Check for missing translations and send alerts if threshold is exceeded';

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
        $threshold = (int) $this->option('threshold');
        $email = $this->option('email');
        $dryRun = $this->option('dry-run');

        $this->info("Checking for missing translations (threshold: {$threshold})...");

        $stats = $this->metricsService->getUsageStatistics();
        $alerts = [];

        foreach ($stats as $locale => $data) {
            $missingCount = 0;
            $missingDetails = [];

            foreach ($data['missing_translations'] as $group => $keys) {
                $count = count($keys);
                $missingCount += $count;
                
                if ($count > 0) {
                    $missingDetails[] = [
                        'group' => $group,
                        'count' => $count,
                        'keys' => $keys
                    ];
                }
            }

            if ($missingCount >= $threshold) {
                $alerts[$locale] = [
                    'total_missing' => $missingCount,
                    'details' => $missingDetails,
                    'error_count' => $data['error_count'],
                    'total_usage' => $data['total_usage']
                ];
            }
        }

        if (empty($alerts)) {
            $this->info('✓ No translation issues found above threshold.');
            return self::SUCCESS;
        }

        // Display alerts
        $this->warn('Translation issues detected:');
        foreach ($alerts as $locale => $alert) {
            $this->line("  <fg=red>{$locale}:</> {$alert['total_missing']} missing translations");
            
            if ($this->output->isVerbose()) {
                foreach ($alert['details'] as $detail) {
                    $this->line("    - {$detail['group']}: {$detail['count']} missing");
                }
            }
        }

        if ($dryRun) {
            $this->info('Dry run mode - no alerts sent.');
            return self::SUCCESS;
        }

        // Log alerts
        Log::warning('Translation alerts triggered', [
            'threshold' => $threshold,
            'alerts' => $alerts,
            'timestamp' => now()->toISOString()
        ]);

        // Send email alert if configured
        if ($email) {
            $this->sendEmailAlert($email, $alerts, $threshold);
        }

        // Create alert file for monitoring systems
        $this->createAlertFile($alerts, $threshold);

        $this->info('Alerts processed successfully.');
        
        return self::SUCCESS;
    }

    /**
     * Send email alert
     */
    protected function sendEmailAlert(string $email, array $alerts, int $threshold): void
    {
        try {
            $subject = 'Translation System Alert - Missing Translations Detected';
            $message = $this->buildAlertMessage($alerts, $threshold);

            // Simple mail sending (you might want to use a proper mail class)
            Mail::raw($message, function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            $this->info("Alert email sent to: {$email}");

        } catch (\Exception $e) {
            $this->error("Failed to send email alert: {$e->getMessage()}");
            Log::error('Translation alert email failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create alert file for monitoring systems
     */
    protected function createAlertFile(array $alerts, int $threshold): void
    {
        try {
            $alertData = [
                'timestamp' => now()->toISOString(),
                'threshold' => $threshold,
                'alerts' => $alerts,
                'summary' => [
                    'total_locales_affected' => count($alerts),
                    'total_missing_translations' => array_sum(array_column($alerts, 'total_missing'))
                ]
            ];

            $filename = 'translation_alerts_' . now()->format('Y-m-d') . '.json';
            $filepath = storage_path("logs/{$filename}");

            file_put_contents($filepath, json_encode($alertData, JSON_PRETTY_PRINT));

            $this->line("Alert file created: {$filepath}");

        } catch (\Exception $e) {
            $this->error("Failed to create alert file: {$e->getMessage()}");
        }
    }

    /**
     * Build alert message content
     */
    protected function buildAlertMessage(array $alerts, int $threshold): string
    {
        $message = "Translation System Alert\n";
        $message .= "========================\n\n";
        $message .= "Missing translations detected above threshold ({$threshold}):\n\n";

        foreach ($alerts as $locale => $alert) {
            $message .= "Locale: {$locale}\n";
            $message .= "- Missing translations: {$alert['total_missing']}\n";
            $message .= "- Recent errors: {$alert['error_count']}\n";
            $message .= "- Total usage: {$alert['total_usage']}\n";
            
            $message .= "- Details:\n";
            foreach ($alert['details'] as $detail) {
                $message .= "  * {$detail['group']}: {$detail['count']} missing keys\n";
            }
            
            $message .= "\n";
        }

        $message .= "Generated at: " . now()->toDateTimeString() . "\n";
        $message .= "Please review and update missing translations.\n";

        return $message;
    }
}
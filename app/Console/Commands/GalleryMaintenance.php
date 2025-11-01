<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageProcessingService;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;

class GalleryMaintenance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gallery:maintenance 
                            {--cleanup : Clean up orphaned files}
                            {--optimize : Optimize existing images}
                            {--stats : Show storage statistics}
                            {--security-scan : Scan images for security threats}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform maintenance tasks on the gallery';

    protected ImageProcessingService $imageService;

    public function __construct(ImageProcessingService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting gallery maintenance...');

        if ($this->option('cleanup')) {
            $this->cleanupOrphanedFiles();
        }

        if ($this->option('optimize')) {
            $this->optimizeExistingImages();
        }

        if ($this->option('stats')) {
            $this->showStorageStats();
        }

        if ($this->option('security-scan')) {
            $this->performSecurityScan();
        }

        if (!$this->hasOption('cleanup') && !$this->hasOption('optimize') && 
            !$this->hasOption('stats') && !$this->hasOption('security-scan')) {
            $this->showHelp();
        }

        $this->info('Gallery maintenance completed.');
    }

    private function cleanupOrphanedFiles()
    {
        $this->info('Cleaning up orphaned files...');
        
        $cleanedFiles = $this->imageService->cleanupOrphanedFiles();
        
        if (empty($cleanedFiles)) {
            $this->info('No orphaned files found.');
        } else {
            $this->info('Cleaned up ' . count($cleanedFiles) . ' orphaned files:');
            foreach ($cleanedFiles as $file) {
                $this->line("  - {$file}");
            }
        }
    }

    private function optimizeExistingImages()
    {
        $this->info('Optimizing existing images...');
        
        $images = GalleryImage::all();
        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $optimized = 0;
        foreach ($images as $image) {
            try {
                $imagePath = Storage::disk('public')->path($image->path);
                if (file_exists($imagePath)) {
                    // Re-optimize the image
                    $this->imageService->setSecureFilePermissions($image->path);
                    $optimized++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to optimize {$image->filename}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Optimized {$optimized} images.");
    }

    private function showStorageStats()
    {
        $this->info('Gallery storage statistics:');
        
        $stats = $this->imageService->getStorageStats();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Images', $stats['total_files']],
                ['Total Thumbnails', $stats['total_thumbnails']],
                ['Total Size (MB)', $stats['total_size_mb']],
                ['Database Records', GalleryImage::count()],
            ]
        );

        if (isset($stats['error'])) {
            $this->error('Error getting stats: ' . $stats['error']);
        }
    }

    private function performSecurityScan()
    {
        $this->info('Performing security scan on gallery images...');
        
        $images = GalleryImage::all();
        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $threats = [];
        foreach ($images as $image) {
            try {
                $imagePath = Storage::disk('public')->path($image->path);
                if (file_exists($imagePath)) {
                    if (!$this->imageService->scanImageForThreats($imagePath)) {
                        $threats[] = $image;
                    }
                }
            } catch (\Exception $e) {
                $this->error("Failed to scan {$image->filename}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        if (empty($threats)) {
            $this->info('No security threats detected.');
        } else {
            $this->error('Security threats detected in ' . count($threats) . ' images:');
            foreach ($threats as $threat) {
                $this->line("  - {$threat->filename} (ID: {$threat->id})");
            }
            $this->warn('Consider removing these images manually.');
        }
    }

    private function showHelp()
    {
        $this->info('Available maintenance options:');
        $this->line('  --cleanup        Clean up orphaned files');
        $this->line('  --optimize       Optimize existing images');
        $this->line('  --stats          Show storage statistics');
        $this->line('  --security-scan  Scan images for security threats');
        $this->newLine();
        $this->info('Example: php artisan gallery:maintenance --cleanup --stats');
    }
}
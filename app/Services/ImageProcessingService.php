<?php

namespace App\Services;

use App\Models\GalleryImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProcessingService
{
    private string $galleryPath = 'gallery';

    private string $thumbnailPath = 'gallery/thumbnails';

    private int $thumbnailWidth = 300;

    private int $thumbnailHeight = 300;

    private int $maxImageWidth = 1920;

    private int $maxImageHeight = 1080;

    private int $jpegQuality = 85;

    private int $webpQuality = 80;

    private int $pngCompressionLevel = 6;

    private bool $enableWebpConversion = true;

    private array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

    private int $maxFileSize = 5242880; // 5MB in bytes

    public function __construct()
    {
        // Ensure GD extension is loaded
        if (! extension_loaded('gd')) {
            throw new \Exception('GD extension is required for image processing');
        }

        // Load configuration values
        $this->maxFileSize = config('gallery.security.max_file_size', 5242880);
        $this->allowedMimeTypes = config('gallery.security.allowed_mime_types', ['image/jpeg', 'image/png', 'image/webp']);
        $this->maxImageWidth = config('gallery.performance.max_image_width', 1920);
        $this->maxImageHeight = config('gallery.performance.max_image_height', 1080);
        $this->jpegQuality = config('gallery.performance.jpeg_quality', 85);
        $this->webpQuality = config('gallery.performance.webp_quality', 80);
        $this->pngCompressionLevel = config('gallery.performance.png_compression_level', 6);
        $this->thumbnailWidth = config('gallery.performance.thumbnail_width', 300);
        $this->thumbnailHeight = config('gallery.performance.thumbnail_height', 300);
        $this->enableWebpConversion = config('gallery.performance.enable_webp_conversion', true);
        $this->galleryPath = config('gallery.storage.gallery_path', 'gallery');
        $this->thumbnailPath = config('gallery.storage.thumbnail_path', 'gallery/thumbnails');
    }

    /**
     * Process multiple gallery images from upload
     *
     * @param  array  $uploadedFiles  Array of UploadedFile instances
     * @return array Array of processed image data
     *
     * @throws \Exception
     */
    public function processGalleryImages(array $uploadedFiles): array
    {
        $processedImages = [];

        foreach ($uploadedFiles as $file) {
            if (! $this->validateImageFile($file)) {
                throw new \Exception("Invalid image file: {$file->getClientOriginalName()}");
            }

            $processedImage = $this->processSingleImage($file);
            $processedImages[] = $processedImage;
        }

        return $processedImages;
    }

    /**
     * Process a single image file
     *
     * @throws \Exception
     */
    private function processSingleImage(UploadedFile $file): array
    {
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file->getClientOriginalName());

        // Define paths
        $imagePath = $this->galleryPath.'/'.$filename;
        $thumbnailPath = $this->thumbnailPath.'/'.$this->getThumbnailFilename($filename);

        try {
            // Store original file temporarily to process it
            $tempPath = $file->store('temp');
            $fullTempPath = Storage::path($tempPath);

            // Process and optimize main image
            $this->processAndStoreImage($fullTempPath, $imagePath);

            // Create thumbnail
            $this->createThumbnail($fullTempPath, $thumbnailPath);

            // Clean up temporary file
            Storage::delete($tempPath);

            return [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $imagePath,
                'thumbnail_path' => $thumbnailPath,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];

        } catch (\Exception $e) {
            // Clean up any created files on error
            Storage::disk('public')->delete([$imagePath, $thumbnailPath]);
            if (isset($tempPath)) {
                Storage::delete($tempPath);
            }
            throw new \Exception("Failed to process image {$file->getClientOriginalName()}: ".$e->getMessage());
        }
    }

    /**
     * Create thumbnail from image
     *
     * @param  string  $sourcePath  Full path to source image
     * @param  string  $thumbnailPath  Relative path for thumbnail storage
     * @return string Thumbnail path
     *
     * @throws \Exception
     */
    public function createThumbnail(string $sourcePath, string $thumbnailPath): string
    {
        try {
            // Create thumbnail using GD
            $thumbnailData = $this->resizeImage(
                $sourcePath,
                $this->thumbnailWidth,
                $this->thumbnailHeight,
                true
            );

            // Store thumbnail
            Storage::disk('public')->put($thumbnailPath, $thumbnailData);

            return $thumbnailPath;

        } catch (\Exception $e) {
            throw new \Exception('Failed to create thumbnail: '.$e->getMessage());
        }
    }

    /**
     * Process and optimize image for web display with security checks
     *
     * @throws \Exception
     */
    private function processAndStoreImage(string $sourcePath, string $destinationPath): void
    {
        try {
            // Security scan before processing
            if (! $this->scanImageForThreats($sourcePath)) {
                throw new \Exception('Image failed security scan');
            }

            // Get image info
            $imageInfo = getimagesize($sourcePath);
            if (! $imageInfo) {
                throw new \Exception('Invalid image file');
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];

            // Check if resize is needed
            if ($width > $this->maxImageWidth || $height > $this->maxImageHeight) {
                // Resize image
                $resizedData = $this->resizeImage(
                    $sourcePath,
                    $this->maxImageWidth,
                    $this->maxImageHeight,
                    false
                );
                Storage::disk('public')->put($destinationPath, $resizedData);
            } else {
                // Just optimize the image
                $optimizedData = $this->optimizeImage($sourcePath);
                Storage::disk('public')->put($destinationPath, $optimizedData);
            }

            // Set secure file permissions
            $this->setSecureFilePermissions($destinationPath);

        } catch (\Exception $e) {
            throw new \Exception('Failed to process and store image: '.$e->getMessage());
        }
    }

    /**
     * Generate unique filename to avoid conflicts with enhanced security
     */
    public function generateUniqueFilename(string $originalName): string
    {
        // Sanitize the original filename first
        $originalName = $this->sanitizeFilename($originalName);

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);

        // Sanitize and slug the basename
        $baseName = Str::slug($baseName);

        // Ensure we have a valid extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (! in_array($extension, $allowedExtensions)) {
            $extension = 'jpg'; // Default to jpg for security
        }

        // Generate unique identifier with timestamp and random string
        $uniqueId = Str::random(12);
        $timestamp = now()->format('YmdHis');

        // Limit basename length to prevent filesystem issues
        $baseName = substr($baseName, 0, 50);

        return "{$baseName}_{$timestamp}_{$uniqueId}.{$extension}";
    }

    /**
     * Get thumbnail filename from main filename
     */
    private function getThumbnailFilename(string $filename): string
    {
        $pathInfo = pathinfo($filename);

        return $pathInfo['filename'].'_thumb.'.$pathInfo['extension'];
    }

    /**
     * Validate uploaded image file with enhanced security checks
     */
    private function validateImageFile(UploadedFile $file): bool
    {
        // Check if file is valid
        if (! $file->isValid()) {
            return false;
        }

        // Check MIME type
        if (! in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            return false;
        }

        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            return false;
        }

        // Enhanced security validation
        try {
            $imageInfo = getimagesize($file->getPathname());
            if (! $imageInfo) {
                return false;
            }

            // Check image dimensions (prevent extremely large images)
            $width = $imageInfo[0];
            $height = $imageInfo[1];

            if ($width > 8000 || $height > 8000) {
                return false;
            }

            // Verify MIME type matches file extension
            $detectedMime = $imageInfo['mime'];
            if ($detectedMime !== $file->getMimeType()) {
                return false;
            }

            // Additional security: check for embedded PHP code
            $fileContent = file_get_contents($file->getPathname());
            if (strpos($fileContent, '<?php') !== false || strpos($fileContent, '<?=') !== false) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sanitize filename to prevent directory traversal and other attacks
     */
    private function sanitizeFilename(string $filename): string
    {
        // Remove directory traversal attempts
        $filename = basename($filename);

        // Remove null bytes and other dangerous characters
        $filename = str_replace(["\0", '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '', $filename);

        // Limit length
        if (strlen($filename) > 255) {
            $pathInfo = pathinfo($filename);
            $extension = isset($pathInfo['extension']) ? '.'.$pathInfo['extension'] : '';
            $basename = substr($pathInfo['filename'], 0, 255 - strlen($extension));
            $filename = $basename.$extension;
        }

        return $filename;
    }

    /**
     * Delete image files from storage
     *
     * @param  GalleryImage|string  $imagePathOrModel
     */
    public function deleteImageFiles($imagePathOrModel, ?string $thumbnailPath = null): bool
    {
        try {
            $deleted = true;

            // Handle both GalleryImage model and direct paths
            if ($imagePathOrModel instanceof GalleryImage) {
                $imagePath = $imagePathOrModel->path;
                $thumbnailPath = $imagePathOrModel->thumbnail_path;
            } else {
                $imagePath = $imagePathOrModel;
                // thumbnailPath should be provided as second parameter
            }

            // Delete main image
            if (Storage::disk('public')->exists($imagePath)) {
                $deleted = Storage::disk('public')->delete($imagePath) && $deleted;
            }

            // Delete thumbnail
            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                $deleted = Storage::disk('public')->delete($thumbnailPath) && $deleted;
            }

            return $deleted;

        } catch (\Exception $e) {
            // Log error but don't throw exception to avoid breaking the application
            \Log::error('Failed to delete image files: '.$e->getMessage(), [
                'image_path' => $imagePath ?? 'unknown',
                'thumbnail_path' => $thumbnailPath ?? 'unknown',
            ]);

            return false;
        }
    }

    /**
     * Clean up orphaned files (files without database records)
     *
     * @return array Array of cleaned up files
     */
    public function cleanupOrphanedFiles(): array
    {
        $cleanedFiles = [];

        try {
            // Get all files in gallery directory
            $galleryFiles = Storage::disk('public')->files($this->galleryPath);
            $thumbnailFiles = Storage::disk('public')->files($this->thumbnailPath);

            // Get all image paths from database
            $dbImagePaths = GalleryImage::pluck('path')->toArray();
            $dbThumbnailPaths = GalleryImage::pluck('thumbnail_path')->toArray();

            // Find orphaned gallery files
            foreach ($galleryFiles as $file) {
                if (! in_array($file, $dbImagePaths)) {
                    Storage::disk('public')->delete($file);
                    $cleanedFiles[] = $file;
                }
            }

            // Find orphaned thumbnail files
            foreach ($thumbnailFiles as $file) {
                if (! in_array($file, $dbThumbnailPaths)) {
                    Storage::disk('public')->delete($file);
                    $cleanedFiles[] = $file;
                }
            }

        } catch (\Exception $e) {
            \Log::error('Failed to cleanup orphaned files: '.$e->getMessage());
        }

        return $cleanedFiles;
    }

    /**
     * Ensure gallery directories exist
     */
    public function ensureDirectoriesExist(): void
    {
        Storage::disk('public')->makeDirectory($this->galleryPath);
        Storage::disk('public')->makeDirectory($this->thumbnailPath);
    }

    /**
     * Get storage statistics
     */
    public function getStorageStats(): array
    {
        try {
            $galleryFiles = Storage::disk('public')->files($this->galleryPath);
            $thumbnailFiles = Storage::disk('public')->files($this->thumbnailPath);

            $totalSize = 0;
            foreach (array_merge($galleryFiles, $thumbnailFiles) as $file) {
                $totalSize += Storage::disk('public')->size($file);
            }

            return [
                'total_files' => count($galleryFiles),
                'total_thumbnails' => count($thumbnailFiles),
                'total_size_bytes' => $totalSize,
                'total_size_mb' => round($totalSize / (1024 * 1024), 2),
            ];

        } catch (\Exception $e) {
            return [
                'total_files' => 0,
                'total_thumbnails' => 0,
                'total_size_bytes' => 0,
                'total_size_mb' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Resize image using GD library
     *
     * @param  bool  $crop  Whether to crop to exact dimensions or maintain aspect ratio
     * @return string Binary image data
     *
     * @throws \Exception
     */
    private function resizeImage(string $sourcePath, int $maxWidth, int $maxHeight, bool $crop = false): string
    {
        $imageInfo = getimagesize($sourcePath);
        if (! $imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Create source image resource
        $sourceImage = $this->createImageFromFile($sourcePath, $mimeType);
        if (! $sourceImage) {
            throw new \Exception('Failed to create image resource');
        }

        // Calculate new dimensions
        if ($crop) {
            // For thumbnails, crop to exact dimensions
            $newWidth = $maxWidth;
            $newHeight = $maxHeight;

            // Calculate crop area to center the image
            $sourceRatio = $originalWidth / $originalHeight;
            $targetRatio = $maxWidth / $maxHeight;

            if ($sourceRatio > $targetRatio) {
                // Source is wider, crop width
                $cropWidth = $originalHeight * $targetRatio;
                $cropHeight = $originalHeight;
                $cropX = ($originalWidth - $cropWidth) / 2;
                $cropY = 0;
            } else {
                // Source is taller, crop height
                $cropWidth = $originalWidth;
                $cropHeight = $originalWidth / $targetRatio;
                $cropX = 0;
                $cropY = ($originalHeight - $cropHeight) / 2;
            }
        } else {
            // Maintain aspect ratio
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = (int) ($originalWidth * $ratio);
            $newHeight = (int) ($originalHeight * $ratio);

            $cropX = 0;
            $cropY = 0;
            $cropWidth = $originalWidth;
            $cropHeight = $originalHeight;
        }

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        if (! $newImage) {
            imagedestroy($sourceImage);
            throw new \Exception('Failed to create new image resource');
        }

        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefill($newImage, 0, 0, $transparent);
        }

        // Resize image
        $success = imagecopyresampled(
            $newImage, $sourceImage,
            0, 0, (int) $cropX, (int) $cropY,
            $newWidth, $newHeight, (int) $cropWidth, (int) $cropHeight
        );

        if (! $success) {
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            throw new \Exception('Failed to resize image');
        }

        // Get image data
        ob_start();
        imagejpeg($newImage, null, $this->jpegQuality);
        $imageData = ob_get_clean();

        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        if (! $imageData) {
            throw new \Exception('Failed to generate image data');
        }

        return $imageData;
    }

    /**
     * Optimize image without resizing with enhanced compression
     *
     * @return string Binary image data
     *
     * @throws \Exception
     */
    private function optimizeImage(string $sourcePath): string
    {
        $imageInfo = getimagesize($sourcePath);
        if (! $imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $mimeType = $imageInfo['mime'];
        $sourceImage = $this->createImageFromFile($sourcePath, $mimeType);

        if (! $sourceImage) {
            throw new \Exception('Failed to create image resource');
        }

        // Apply image optimization based on type and settings
        ob_start();

        if ($this->enableWebpConversion && function_exists('imagewebp')) {
            // Convert to WebP for better compression
            imagewebp($sourceImage, null, $this->webpQuality);
        } elseif ($mimeType === 'image/png') {
            // Optimize PNG with compression
            imagepng($sourceImage, null, $this->pngCompressionLevel);
        } else {
            // Default to optimized JPEG
            imagejpeg($sourceImage, null, $this->jpegQuality);
        }

        $imageData = ob_get_clean();

        imagedestroy($sourceImage);

        if (! $imageData) {
            throw new \Exception('Failed to optimize image');
        }

        return $imageData;
    }

    /**
     * Apply advanced image optimization techniques
     *
     * @param  resource  $image
     * @return resource
     */
    private function applyImageOptimizations($image)
    {
        // Apply sharpening filter for better quality after resize
        if (function_exists('imagefilter')) {
            imagefilter($image, IMG_FILTER_SHARPEN);
        }

        return $image;
    }

    /**
     * Set proper file permissions for uploaded images
     */
    public function setSecureFilePermissions(string $filePath): bool
    {
        try {
            // Set read-only permissions for uploaded files (644)
            return chmod(Storage::disk('public')->path($filePath), 0644);
        } catch (\Exception $e) {
            \Log::warning("Failed to set file permissions for: {$filePath}", [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Scan image for potential security threats
     */
    public function scanImageForThreats(string $filePath): bool
    {
        try {
            $content = file_get_contents($filePath);

            // Check for embedded scripts or suspicious content
            $suspiciousPatterns = [
                '/<\?php/i',
                '/<\?=/i',
                '/<script/i',
                '/javascript:/i',
                '/vbscript:/i',
                '/onload=/i',
                '/onerror=/i',
                '/eval\(/i',
                '/base64_decode/i',
            ];

            foreach ($suspiciousPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create image resource from file
     *
     * @return resource|false
     */
    private function createImageFromFile(string $filePath, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($filePath);
            case 'image/png':
                return imagecreatefrompng($filePath);
            case 'image/webp':
                return imagecreatefromwebp($filePath);
            case 'image/gif':
                return imagecreatefromgif($filePath);
            default:
                return false;
        }
    }
}

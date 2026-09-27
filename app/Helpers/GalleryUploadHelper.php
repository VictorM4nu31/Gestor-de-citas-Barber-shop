<?php

namespace App\Helpers;

class GalleryUploadHelper
{
    /**
     * Get the effective per-image size ceiling in kilobytes.
     *
     * The configured limit is capped by PHP's own upload_max_filesize, because
     * PHP rejects oversized uploads before any application rule can run. Exposing
     * the effective value keeps the interface and the validator in agreement.
     */
    public static function maxFileSizeKb(): int
    {
        $configuredKb = (int) round(config('gallery.security.max_file_size', 5242880) / 1024);
        $phpLimitKb = self::phpUploadLimitKb();

        return $phpLimitKb === null
            ? $configuredKb
            : min($configuredKb, $phpLimitKb);
    }

    /**
     * Get the number of images allowed in a single upload request.
     */
    public static function maxFilesPerUpload(): int
    {
        return (int) config('gallery.security.max_files_per_upload', 10);
    }

    /**
     * Get the effective per-image size ceiling in bytes.
     */
    public static function maxFileSizeBytes(): int
    {
        return self::maxFileSizeKb() * 1024;
    }

    /**
     * Format a kilobyte value for display, e.g. 5120 becomes "5 MB".
     */
    public static function formatKb(int $kilobytes): string
    {
        if ($kilobytes <= 0) {
            return '—';
        }

        if ($kilobytes < 1024) {
            return $kilobytes.' KB';
        }

        return rtrim(rtrim(number_format($kilobytes / 1024, 1), '0'), '.').' MB';
    }

    /**
     * Get PHP's upload_max_filesize ceiling in kilobytes, or null when unreadable.
     */
    public static function phpUploadLimitKb(): ?int
    {
        $bytes = self::phpUploadLimitBytes();

        return $bytes === null ? null : (int) ceil($bytes / 1024);
    }

    /**
     * Get PHP's upload_max_filesize ceiling in bytes, or null when unreadable.
     */
    public static function phpUploadLimitBytes(): ?int
    {
        $ini = ini_get('upload_max_filesize');

        if (! is_string($ini) || trim($ini) === '') {
            return null;
        }

        $value = trim($ini);
        $amount = (float) $value;

        return match (strtolower(substr($value, -1))) {
            'g' => (int) ($amount * 1024 * 1024 * 1024),
            'm' => (int) ($amount * 1024 * 1024),
            'k' => (int) ($amount * 1024),
            default => (int) $amount,
        };
    }
}

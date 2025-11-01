# Gallery Security & Performance Documentation

## Overview

This document outlines the security and performance optimizations implemented for the admin photo gallery feature.

## Security Features

### 1. Rate Limiting
- **Upload Rate Limiting**: Limits file uploads to 10 attempts per minute per IP address
- **Middleware**: `GalleryUploadRateLimit`
- **Configuration**: Configurable via `GALLERY_RATE_LIMIT_ATTEMPTS` and `GALLERY_RATE_LIMIT_DECAY`

### 2. File Validation & Security
- **MIME Type Validation**: Only allows JPEG, PNG, and WebP images
- **File Size Limits**: Maximum 5MB per file, configurable via `GALLERY_MAX_FILE_SIZE`
- **Dimension Limits**: Prevents extremely large images (max 8000x8000 pixels)
- **Content Scanning**: Scans for embedded PHP code and malicious content
- **Filename Sanitization**: Prevents directory traversal and dangerous characters

### 3. Security Headers
- **X-Content-Type-Options**: `nosniff` to prevent MIME type sniffing
- **X-Frame-Options**: `DENY` to prevent clickjacking
- **X-XSS-Protection**: Enables XSS filtering
- **Content Security Policy**: Restricts resource loading for admin pages
- **Referrer Policy**: Controls referrer information

### 4. File Permissions
- **Secure Permissions**: Sets 644 permissions on uploaded files
- **Directory Protection**: `.htaccess` rules prevent PHP execution in gallery directories
- **Access Control**: Prevents direct access to sensitive files

### 5. CSRF Protection
- **Laravel CSRF**: All form submissions protected by Laravel's CSRF middleware
- **Token Validation**: Automatic token validation on all POST/PUT/DELETE requests

## Performance Optimizations

### 1. Image Processing
- **Automatic Resizing**: Large images resized to max 1920x1080 pixels
- **Quality Optimization**: JPEG quality set to 85%, WebP to 80%
- **Thumbnail Generation**: 300x300 pixel thumbnails for fast loading
- **Format Conversion**: Optional WebP conversion for better compression

### 2. Caching
- **Browser Caching**: 1-year cache headers for static images
- **Immutable Cache**: Images marked as immutable for better caching
- **Cache Control**: Configurable cache settings

### 3. Storage Optimization
- **Unique Filenames**: Prevents conflicts and enables better caching
- **Directory Structure**: Organized storage with separate thumbnail directory
- **Cleanup Tools**: Artisan command for removing orphaned files

## Configuration

### Environment Variables

```env
# Security Settings
GALLERY_MAX_FILE_SIZE=5242880          # 5MB max file size
GALLERY_MAX_FILES_PER_UPLOAD=10        # Max files per upload
GALLERY_RATE_LIMIT_ATTEMPTS=10         # Rate limit attempts
GALLERY_RATE_LIMIT_DECAY=1             # Rate limit decay minutes
GALLERY_SCAN_THREATS=true              # Enable threat scanning

# Performance Settings
GALLERY_PROCESS_MAX_WIDTH=1920         # Max processed image width
GALLERY_PROCESS_MAX_HEIGHT=1080        # Max processed image height
GALLERY_JPEG_QUALITY=85                # JPEG compression quality
GALLERY_WEBP_QUALITY=80                # WebP compression quality
GALLERY_ENABLE_WEBP=true               # Enable WebP conversion
GALLERY_CACHE_MAX_AGE=31536000         # Cache max age (1 year)
```

### Configuration File

The `config/gallery.php` file contains all gallery-related settings with sensible defaults.

## Maintenance Commands

### Gallery Maintenance Command

```bash
# Clean up orphaned files
php artisan gallery:maintenance --cleanup

# Show storage statistics
php artisan gallery:maintenance --stats

# Optimize existing images
php artisan gallery:maintenance --optimize

# Security scan
php artisan gallery:maintenance --security-scan

# Run all maintenance tasks
php artisan gallery:maintenance --cleanup --stats --optimize --security-scan
```

## Security Best Practices

### 1. Regular Maintenance
- Run cleanup commands regularly to remove orphaned files
- Monitor storage usage and file counts
- Perform security scans periodically

### 2. Server Configuration
- Ensure `.htaccess` rules are active (Apache)
- Configure web server to prevent PHP execution in upload directories
- Set proper file system permissions

### 3. Monitoring
- Monitor upload logs for suspicious activity
- Set up alerts for rate limit violations
- Track storage usage and growth

### 4. Updates
- Keep Laravel and dependencies updated
- Review security configurations regularly
- Update file type restrictions as needed

## File Structure

```
storage/app/public/
├── gallery/
│   ├── image1.jpg
│   ├── image2.png
│   ├── .htaccess          # Security rules
│   └── thumbnails/
│       ├── image1_thumb.jpg
│       └── image2_thumb.png
```

## Middleware Stack

1. **Authentication**: Ensures user is logged in
2. **Role Authorization**: Verifies admin role
3. **CSRF Protection**: Validates CSRF tokens
4. **Rate Limiting**: Prevents abuse
5. **Security Headers**: Adds security headers
6. **Image Headers**: Optimizes image delivery

## Logging

All gallery operations are logged with:
- User ID and IP address
- File counts and sizes
- Success/failure status
- Error details for debugging

## Troubleshooting

### Common Issues

1. **Upload Failures**: Check file size, format, and permissions
2. **Rate Limiting**: Wait for rate limit to reset or adjust limits
3. **Security Scan Failures**: Review file content for suspicious patterns
4. **Performance Issues**: Optimize images and check server resources

### Debug Mode

Enable debug logging by setting `LOG_LEVEL=debug` in your `.env` file to get detailed information about gallery operations.
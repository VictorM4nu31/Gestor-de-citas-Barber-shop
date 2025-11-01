# Translation Performance & Monitoring

This document describes the performance optimizations and monitoring features implemented for the internationalization system.

## Performance Optimizations

### Translation Caching

The system implements comprehensive caching for translation files to improve performance:

- **Cached File Loader**: Automatically caches translation files using Laravel's cache system
- **Configurable Cache Duration**: Set cache duration via `TRANSLATION_CACHE_DURATION` environment variable
- **Cache Store Selection**: Choose specific cache store via `TRANSLATION_CACHE_STORE`
- **Preloading Support**: Option to preload all translations for better performance

### Configuration

Add these environment variables to your `.env` file:

```env
# Translation Cache Configuration
TRANSLATION_CACHE_ENABLED=true
TRANSLATION_CACHE_DURATION=null
TRANSLATION_CACHE_STORE=null
TRANSLATION_CACHE_PREFIX=translations
TRANSLATION_PRELOAD_ENABLED=false
TRANSLATION_WARM_CACHE=true
LOG_MISSING_TRANSLATIONS=true
```

### Artisan Commands

#### Cache Management

```bash
# Cache all translation files
php artisan translation:cache

# Cache specific locale only
php artisan translation:cache --locale=es

# Clear translation cache
php artisan translation:cache --clear
```

#### Production Optimization

```bash
# Optimize translations for production
php artisan translation:optimize

# Validate translation files
php artisan translation:optimize --validate

# Minify translation files
php artisan translation:optimize --minify
```

## Monitoring & Metrics

### Automatic Tracking

The system automatically tracks:

- **Language Usage**: Daily and hourly usage statistics per locale
- **Unique Users**: Daily unique user count per locale
- **Missing Translations**: Automatic detection and logging
- **Translation Errors**: Error tracking and reporting

### Metrics Commands

```bash
# View current metrics
php artisan translation:metrics

# View metrics for specific locale
php artisan translation:metrics --locale=es

# Export metrics to JSON
php artisan translation:metrics --export=json

# Export metrics to CSV
php artisan translation:metrics --export=csv

# Clear metrics data
php artisan translation:metrics --clear
```

### Alert System

```bash
# Check for missing translations and send alerts
php artisan translation:alert

# Set custom threshold
php artisan translation:alert --threshold=10

# Send alerts to specific email
php artisan translation:alert --email=admin@example.com

# Dry run (show what would be alerted)
php artisan translation:alert --dry-run
```

### Scheduled Tasks

The system includes automatic daily checks for missing translations:

```php
// Automatically scheduled in routes/console.php
Schedule::command('translation:alert --threshold=5')->daily();
```

### API Endpoints (Admin Only)

For integration with monitoring dashboards:

```
GET /admin/translation-metrics/          # Get usage statistics
GET /admin/translation-metrics/missing   # Get missing translations
DELETE /admin/translation-metrics/clear  # Clear metrics data
```

## Production Deployment

### Recommended Steps

1. **Optimize translations**:
   ```bash
   php artisan translation:optimize --validate --minify
   ```

2. **Cache translations**:
   ```bash
   php artisan translation:cache
   ```

3. **Cache configuration**:
   ```bash
   php artisan config:cache
   ```

4. **Set up monitoring**:
   - Configure email alerts
   - Set up log monitoring
   - Schedule regular metric collection

### Performance Tips

1. **Use Redis or Memcached** for translation caching in production
2. **Enable preloading** for applications with many translation calls
3. **Set up proper logging** to monitor translation performance
4. **Regular cache warming** after deployments
5. **Monitor missing translations** to maintain translation completeness

## Troubleshooting

### Common Issues

1. **Cache not working**: Check cache store configuration and permissions
2. **Missing translations not logged**: Verify `LOG_MISSING_TRANSLATIONS=true`
3. **High memory usage**: Disable preloading or use more efficient cache store
4. **Slow performance**: Enable caching and use appropriate cache store

### Debug Commands

```bash
# Check translation cache status
php artisan translation:metrics

# Validate all translation files
php artisan translation:optimize --validate

# Clear all caches
php artisan cache:clear
php artisan translation:cache --clear
```

## Integration

The performance and monitoring system integrates seamlessly with:

- Laravel's built-in translation system
- Existing localization middleware
- Cache management system
- Logging infrastructure
- Task scheduling system

All features are designed to work without breaking existing functionality while providing comprehensive performance improvements and monitoring capabilities.
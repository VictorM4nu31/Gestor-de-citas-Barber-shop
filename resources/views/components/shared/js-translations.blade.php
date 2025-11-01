@props([
    'keys' => [],
    'locale' => null
])

@php
    $currentLocale = $locale ?? app()->getLocale();
    $translations = [];
    
    // If specific keys are provided, only include those
    if (!empty($keys)) {
        foreach ($keys as $key) {
            $value = __($key, [], $currentLocale);
            if ($value !== $key) {
                data_set($translations, $key, $value);
            }
        }
    } else {
        // Include common translations for JavaScript
        $commonKeys = [
            // UI translations
            'common.ui.close',
            'common.ui.cancel',
            'common.ui.save',
            'common.ui.confirm',
            'common.ui.delete',
            'common.ui.edit',
            'common.ui.loading',
            'common.ui.submit',
            
            // Gallery translations
            'gallery.admin.modals.upload.title',
            'gallery.admin.modals.upload.uploading',
            'gallery.admin.modals.upload.upload_button',
            'gallery.admin.modals.upload.uploading_button',
            'gallery.admin.modals.upload.cancel',
            'gallery.admin.modals.edit.title',
            'gallery.admin.modals.edit.save_changes',
            'gallery.admin.modals.edit.saving',
            'gallery.admin.modals.edit.cancel',
            
            // Messages
            'messages.success.image_updated',
            'messages.success.order_saved',
            'messages.error.upload_failed',
            'messages.error.connection_error',
            'messages.error.server_error',
            'messages.info.changes_cancelled',
            
            // Upload manager messages
            'gallery.admin.upload.validation.max_files',
            'gallery.admin.upload.validation.file_type',
            'gallery.admin.upload.validation.file_size',
            'gallery.admin.upload.status.pending',
            'gallery.admin.upload.status.uploading',
            'gallery.admin.upload.status.completed',
            'gallery.admin.upload.status.error',
            'gallery.admin.upload.status.preparing',
            'gallery.admin.upload.status.queue',
            'gallery.admin.upload.errors.connection',
            'gallery.admin.upload.errors.offline',
            'gallery.admin.upload.errors.server',
            'gallery.admin.upload.errors.unknown',
            
            // Reorder messages
            'gallery.admin.reorder.drop_here',
            'gallery.admin.reorder.save_order',
            'gallery.admin.reorder.saving',
            'gallery.admin.reorder.cancel_order',
            'gallery.admin.reorder.changes_cancelled',
            'gallery.admin.reorder.order_saved',
            'gallery.admin.reorder.save_error'
        ];
        
        foreach ($commonKeys as $key) {
            $value = __($key, [], $currentLocale);
            if ($value !== $key) {
                data_set($translations, $key, $value);
            }
        }
    }
    
    // Create nested structure for the current locale
    $localeTranslations = [$currentLocale => $translations];
@endphp

<script data-translations type="application/json">
{!! json_encode($localeTranslations, JSON_UNESCAPED_UNICODE) !!}
</script>
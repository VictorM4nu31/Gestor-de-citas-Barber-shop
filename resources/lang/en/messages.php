<?php

return [
    // General success messages
    'success' => [
        'created' => ':item created successfully.',
        'updated' => ':item updated successfully.',
        'deleted' => ':item deleted successfully.',
        'saved' => ':item saved successfully.',
        'activated' => ':item activated successfully.',
        'deactivated' => ':item deactivated successfully.',
        'restored' => ':item restored successfully.',
        'operation_completed' => 'Operation completed successfully.',
        'changes_saved' => 'Changes have been saved successfully.',
        'action_completed' => 'Action completed successfully.',
    ],

    // General error messages
    'error' => [
        'general' => 'An unexpected error has occurred.',
        'not_found' => ':item not found.',
        'unauthorized' => 'You do not have permission to perform this action.',
        'forbidden' => 'Access denied.',
        'validation_failed' => 'The provided data is not valid.',
        'operation_failed' => 'The operation could not be completed.',
        'server_error' => 'Internal server error.',
        'network_error' => 'Connection error. Please check your internet connection.',
        'timeout' => 'The operation has timed out.',
        'file_upload_failed' => 'File upload failed.',
        'file_too_large' => 'The file is too large.',
        'invalid_file_type' => 'Invalid file type.',
        'database_error' => 'Database error.',
        'permission_denied' => 'Insufficient permissions for this operation.',
    ],

    // Confirmation messages
    'confirmation' => [
        'delete' => 'Are you sure you want to delete this :item?',
        'delete_multiple' => 'Are you sure you want to delete the selected items?',
        'deactivate' => 'Are you sure you want to deactivate this :item?',
        'activate' => 'Are you sure you want to activate this :item?',
        'restore' => 'Are you sure you want to restore this :item?',
        'permanent_delete' => 'Are you sure you want to permanently delete this :item? This action cannot be undone.',
        'discard_changes' => 'Are you sure you want to discard the changes?',
        'leave_page' => 'You have unsaved changes. Are you sure you want to leave?',
        'action_irreversible' => 'This action cannot be undone.',
        'continue_action' => 'Do you want to continue?',
    ],

    // Notification messages
    'notification' => [
        'new_message' => 'You have a new message.',
        'new_appointment' => 'New appointment scheduled.',
        'appointment_reminder' => 'Reminder: You have an appointment at :time.',
        'appointment_cancelled' => 'Your appointment has been cancelled.',
        'appointment_confirmed' => 'Your appointment has been confirmed.',
        'system_maintenance' => 'The system will be under maintenance from :start to :end.',
        'update_available' => 'An update is available.',
        'backup_completed' => 'Backup completed successfully.',
        'data_exported' => 'Data has been exported successfully.',
        'email_sent' => 'Email sent successfully.',
        'password_changed' => 'Your password has been changed successfully.',
    ],

    // Barber-specific messages
    'barber' => [
        'created' => 'Barber created successfully.',
        'updated' => 'Barber updated successfully.',
        'deleted' => 'Barber deleted successfully.',
        'deactivated' => 'Barber deactivated successfully.',
        'reactivated' => 'Barber reactivated successfully.',
        'permanently_deleted' => 'Barber permanently deleted from the system.',
        'not_found' => 'Barber not found or inactive.',
        'create_error' => 'Error creating barber: :error',
        'update_error' => 'Error updating barber: :error',
        'deactivate_error' => 'Error deactivating barber: :error',
        'reactivate_error' => 'Error reactivating barber: :error',
        'delete_error' => 'Error deleting barber: :error',
    ],

    // Appointment-specific messages
    'appointment' => [
        'created' => 'Appointment scheduled successfully.',
        'updated' => 'Appointment updated successfully.',
        'deleted' => 'Appointment deleted successfully.',
        'cancelled' => 'Appointment cancelled successfully.',
        'attended' => 'Appointment marked as attended successfully.',
        'max_appointments' => 'You already have 2 pending appointments, you cannot schedule a third one.',
        'no_availability' => 'No availability, make sure you have chosen one of the available time slots.',
        'cannot_attend' => 'This appointment cannot be marked as attended.',
        'past_appointment' => 'You cannot schedule an appointment in the past.',
        'duplicate_appointment' => 'You already have an appointment scheduled for this date and time.',
        'barber_unavailable' => 'The barber is not available at the selected time.',
    ],

    // Service-specific messages
    'service' => [
        'created' => 'Service created successfully.',
        'updated' => 'Service updated successfully.',
        'deleted' => 'Service deleted successfully.',
        'not_found' => 'Service not found.',
        'in_use' => 'Cannot delete service because it is being used.',
    ],

    // User-specific messages
    'user' => [
        'created' => 'User created successfully.',
        'updated' => 'User updated successfully.',
        'deleted' => 'User deleted successfully.',
        'activated' => 'User activated successfully.',
        'deactivated' => 'User deactivated successfully.',
        'profile_updated' => 'Profile updated successfully.',
        'password_updated' => 'Password updated successfully.',
        'email_verified' => 'Email verified successfully.',
        'not_found' => 'User not found.',
        'already_exists' => 'A user with this email already exists.',
    ],

    // Gallery-specific messages
    'gallery' => [
        'image_uploaded' => 'Image uploaded successfully.',
        'images_uploaded' => ':count images uploaded successfully.',
        'image_updated' => 'Image updated successfully.',
        'image_deleted' => 'Image deleted successfully.',
        'images_deleted' => ':count images deleted successfully.',
        'order_updated' => 'Image order updated successfully.',
        'upload_failed' => 'Image upload failed.',
        'invalid_format' => 'Invalid image format. Supported formats: JPG, PNG, GIF, WEBP.',
        'file_too_large' => 'Image is too large. Maximum size: :size MB.',
        'no_images_selected' => 'No images selected.',
        'processing_images' => 'Processing images...',
        'upload_complete' => 'Upload complete.',
    ],

    // System status messages
    'system' => [
        'online' => 'System online.',
        'offline' => 'System offline.',
        'maintenance' => 'System under maintenance.',
        'loading' => 'Loading...',
        'processing' => 'Processing...',
        'saving' => 'Saving...',
        'deleting' => 'Deleting...',
        'uploading' => 'Uploading...',
        'downloading' => 'Downloading...',
        'connecting' => 'Connecting...',
        'disconnected' => 'Disconnected.',
        'reconnecting' => 'Reconnecting...',
        'session_expired' => 'Your session has expired. Please log in again.',
        'unauthorized_access' => 'Unauthorized access detected.',
    ],

    // Custom validation messages
    'validation' => [
        'custom_required' => 'The :attribute field is required.',
        'custom_email' => 'The :attribute field must be a valid email address.',
        'custom_unique' => 'The :attribute is already in use.',
        'custom_min_length' => 'The :attribute must be at least :min characters.',
        'custom_max_length' => 'The :attribute cannot be more than :max characters.',
        'custom_numeric' => 'The :attribute must be a number.',
        'custom_date' => 'The :attribute must be a valid date.',
        'custom_time' => 'The :attribute must be a valid time.',
        'custom_phone' => 'The :attribute must be a valid phone number.',
        'custom_password' => 'The password must be at least 8 characters long and include uppercase, lowercase, and numbers.',
        'passwords_match' => 'The passwords do not match.',
        'current_password' => 'The current password is incorrect.',
    ],

    // Information messages
    'info' => [
        'no_data' => 'No data available.',
        'empty_list' => 'The list is empty.',
        'search_no_results' => 'No results found for your search.',
        'filter_no_results' => 'No items match the applied filters.',
        'coming_soon' => 'Coming soon.',
        'feature_disabled' => 'This feature is temporarily disabled.',
        'beta_feature' => 'This is a beta feature.',
        'data_updated' => 'Data has been updated.',
        'auto_save' => 'Auto-save enabled.',
        'changes_detected' => 'Changes detected.',
        'unsaved_changes' => 'You have unsaved changes.',
    ],

    // Help messages
    'help' => [
        'tooltip_edit' => 'Click to edit.',
        'tooltip_delete' => 'Click to delete.',
        'tooltip_view' => 'Click to view details.',
        'tooltip_download' => 'Click to download.',
        'tooltip_upload' => 'Drag files here or click to select.',
        'keyboard_shortcut' => 'Keyboard shortcut: :shortcut',
        'required_fields' => 'Fields marked with * are required.',
        'optional_field' => 'Optional field.',
        'format_hint' => 'Expected format: :format',
        'example' => 'Example: :example',
    ],
];
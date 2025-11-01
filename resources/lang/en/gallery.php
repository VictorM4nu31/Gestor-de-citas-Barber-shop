<?php

return [
    'title' => 'Our Gallery',
    'description' => 'Discover our work and the atmosphere of our barbershop',
    'view_image' => 'View image',
    'close' => 'Close',
    'previous' => 'Previous',
    'next' => 'Next',
    'image_of' => 'Image :current of :total',
    'no_images' => 'No images available in the gallery.',
    'image_alt_default' => 'Gallery image',
    'errors' => [
        'load_failed' => 'Error loading image'
    ],
    
    'admin' => [
        'basic_info' => 'Basic Information',
        'technical_info' => 'Technical Information',
        'original_name' => 'Original Name',
        'alt_text' => 'Alt Text',
        'not_specified' => 'Not specified',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'file_size' => 'File Size',
        'mime_type' => 'MIME Type',
        'display_order' => 'Display Order',
        'upload_date' => 'Upload Date',
        'last_modified' => 'Last Modified',
        'access_urls' => 'Access URLs',
        'image_url' => 'Image URL',
        'thumbnail_url' => 'Thumbnail URL',
        'copy_url' => 'Copy URL',
        'url_copied' => 'URL copied to clipboard',
        'actions' => [
            'edit_image' => 'Edit Image',
            'delete_image' => 'Delete Image',
            'set_as_featured' => 'Set as Featured',
            'change_order' => 'Change Order',
            'view_details' => 'View Details',
            'download' => 'Download'
        ],
        'upload' => [
            'title' => 'Upload New Images',
            'select_files' => 'Select Files',
            'drag_drop' => 'Drag and drop images here',
            'or' => 'or',
            'click_to_browse' => 'click to browse',
            'max_file_size' => 'Max file size: 10MB per file',
            'allowed_formats' => 'Allowed formats: JPG, PNG, GIF, WEBP',
            'uploading' => 'Uploading...',
            'upload_success' => 'Images uploaded successfully',
            'upload_error' => 'Error uploading images'
        ],
        'management' => [
            'total_images' => '{0} images|{1} image|[2,*] images',
            'active_images' => 'Active Images',
            'inactive_images' => 'Inactive Images',
            'storage_used' => 'Storage Used',
            'last_upload' => 'Last Upload',
            'bulk_actions' => 'Bulk Actions',
            'select_all' => 'Select All',
            'deselect_all' => 'Deselect All',
            'activate_selected' => 'Activate Selected',
            'deactivate_selected' => 'Deactivate Selected',
            'delete_selected' => 'Delete Selected',
            'search_images' => 'Search images',
            'search_placeholder' => 'Search by name or alt text...',
            'status_filter' => 'Status',
            'all_status' => 'All',
            'active_status' => 'Active',
            'inactive_status' => 'Inactive',
            'selected_count' => '{0} images selected|{1} image selected|[2,*] images selected',
            'change_status' => 'Change Status',
            'reorder_images' => 'Reorder',
            'cancel_order' => 'Cancel Order',
            'drag_to_reorder' => 'Drag images to change their display order',
            'save_order' => 'Save Order',
            'order_saved' => 'Order saved successfully',
            'order_error' => 'Error saving order'
        ],
        'modals' => [
            'upload' => [
                'title' => 'Upload Images',
                'select_images' => 'Select Images',
                'drag_drop_hint' => 'Drag images here or',
                'click_to_select' => 'click to select',
                'supported_formats' => 'Supported formats: JPG, PNG, GIF, WEBP (max. 10MB each)',
                'selected_images' => 'Selected images',
                'uploading' => 'Uploading images...',
                'upload_button' => 'Upload Images',
                'uploading_button' => 'Uploading...',
                'cancel' => 'Cancel'
            ],
            'edit' => [
                'title' => 'Edit Image',
                'alt_text_label' => 'Alt Text',
                'alt_text_placeholder' => 'Describe the image for accessibility...',
                'alt_text_help' => 'Helps screen readers and improves SEO',
                'active_checkbox' => 'Active image (visible in gallery)',
                'image_info_title' => 'Image information',
                'name_label' => 'Name:',
                'size_label' => 'Size:',
                'order_label' => 'Order:',
                'uploaded_label' => 'Uploaded:',
                'save_changes' => 'Save Changes',
                'saving' => 'Saving...',
                'cancel' => 'Cancel'
            ]
        ],
        'upload' => [
            'validation' => [
                'max_files' => 'Maximum :max files allowed',
                'file_type' => 'File type not allowed. Use JPG, PNG or WEBP.',
                'file_size' => 'File too large. Maximum :maxMB allowed.'
            ],
            'status' => [
                'pending' => 'Queued...',
                'uploading' => 'Uploading... :progress%',
                'completed' => 'Completed',
                'error' => 'Upload error',
                'preparing' => 'Preparing...',
                'queue' => 'Queued...'
            ],
            'errors' => [
                'connection' => 'Connection error with server',
                'offline' => 'No internet connection',
                'server' => 'Server error: :status',
                'unknown' => 'Unknown error'
            ]
        ],
        'reorder' => [
            'drop_here' => 'Drop here',
            'save_order' => 'Save Order',
            'saving' => 'Saving...',
            'cancel_order' => 'Cancel',
            'changes_cancelled' => 'Changes cancelled',
            'order_saved' => 'Order saved successfully',
            'save_error' => 'Error saving order'
        ]
    ]
];
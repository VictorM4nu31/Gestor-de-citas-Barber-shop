<?php

return [
    'title' => 'Our Barbers',
    'description' => 'Meet our talented barbers who are ready to serve you.',
    'specialties' => 'Specialties',
    'experience' => 'Experience',
    'years' => 'years',
    'book_with' => 'Book with :name',
    'no_barberos' => 'No barbers available at the moment.',
    'profile_of' => 'Profile of',
    'back_to_list' => 'Back to List',
    'email' => 'Email',
    'phone' => 'Phone',
    'specialty' => 'Specialty',
    'book_appointment_with' => 'Book Appointment with',
    
    // Administrative section
    'admin' => [
        'titles' => [
            'create' => 'Create Barber',
            'edit' => 'Edit Barber',
            'show' => 'View Barber',
            'list' => 'Barbers List',
            'management_actions' => 'Management Actions'
        ],
        'buttons' => [
            'create_barber' => 'Create Barber',
            'edit_barber' => 'Edit Barber',
            'save' => 'Save',
            'back_to_list' => 'Back to List',
            'back_to_index' => 'Back to list',
            'edit' => 'Edit',
            'deactivate' => 'Deactivate',
            'reactivate' => 'Reactivate',
            'reactivate_barber' => 'Reactivate Barber',
            'delete_permanent' => 'Delete Permanently'
        ],
        'labels' => [
            'full_name' => 'Full Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',
            'password_keep_current' => 'Password (leave blank to keep current)',
            'phone' => 'Phone',
            'specialty' => 'Specialty',
            'experience' => 'Experience',
            'photo' => 'Photo',
            'services_offered' => 'Services Offered',
            'status' => 'Status',
            'deactivation_date' => 'Deactivation Date',
            'current_services' => 'Current Services'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'all' => 'All',
            'actives' => 'Active',
            'inactives' => 'Inactive',
            'since' => 'Since',
            'deactivated_on' => 'Deactivated on'
        ],
        'filters' => [
            'all' => 'All',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'showing_count' => 'Showing :count barber|Showing :count barbers'
        ],
        'table' => [
            'id' => 'ID',
            'status' => 'Status',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'specialty' => 'Specialty',
            'experience' => 'Experience',
            'photo' => 'Photo',
            'actions' => 'Actions',
            'no_photo' => 'No photo'
        ],
        'messages' => [
            'file_requirements' => 'Maximum size: 2MB. Allowed formats: jpeg, png, jpg.',
            'services_help' => 'Select the services this barber can offer. Only published services will be shown.',
            'no_services_available' => 'No published services available.',
            'services_assigned' => 'Current services: :count assigned. Modify the selection to change the services this barber can offer.',
            'price_duration_format' => ':price • :duration min'
        ],
        'confirmations' => [
            'deactivate' => 'Are you sure you want to deactivate this barber?\n\nThe barber:\n• Will not be able to access the system\n• Their appointment history will be maintained\n• Can be reactivated later',
            'reactivate' => 'Are you sure you want to reactivate this barber?\n\nThe barber will be able to access the system again.',
            'deactivate_simple' => 'Are you sure you want to deactivate this barber? They will not be able to access the system but their history will be maintained.',
            'reactivate_simple' => 'Are you sure you want to reactivate this barber?'
        ],
        'empty_states' => [
            'no_barbers' => 'No barbers registered',
            'no_active_barbers' => 'No active barbers',
            'no_inactive_barbers' => 'No inactive barbers',
            'no_barbers_description' => 'Start by creating your first barber.',
            'no_active_description' => 'All barbers are deactivated or there are no registered barbers.',
            'no_inactive_description' => 'All barbers are active.'
        ]
    ]
];
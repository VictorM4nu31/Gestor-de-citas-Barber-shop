<?php

return [
    // Mensajes de éxito generales
    'success' => [
        'created' => ':item creado exitosamente.',
        'updated' => ':item actualizado exitosamente.',
        'deleted' => ':item eliminado exitosamente.',
        'saved' => ':item guardado exitosamente.',
        'activated' => ':item activado exitosamente.',
        'deactivated' => ':item desactivado exitosamente.',
        'restored' => ':item restaurado exitosamente.',
        'operation_completed' => 'Operación completada exitosamente.',
        'changes_saved' => 'Los cambios han sido guardados exitosamente.',
        'action_completed' => 'Acción completada exitosamente.',
    ],

    // Mensajes de error generales
    'error' => [
        'general' => 'Ha ocurrido un error inesperado.',
        'not_found' => ':item no encontrado.',
        'unauthorized' => 'No tienes permisos para realizar esta acción.',
        'forbidden' => 'Acceso denegado.',
        'validation_failed' => 'Los datos proporcionados no son válidos.',
        'operation_failed' => 'La operación no pudo completarse.',
        'server_error' => 'Error interno del servidor.',
        'network_error' => 'Error de conexión. Verifica tu conexión a internet.',
        'timeout' => 'La operación ha excedido el tiempo límite.',
        'file_upload_failed' => 'Error al subir el archivo.',
        'file_too_large' => 'El archivo es demasiado grande.',
        'invalid_file_type' => 'Tipo de archivo no válido.',
        'database_error' => 'Error en la base de datos.',
        'permission_denied' => 'Permisos insuficientes para esta operación.',
    ],

    // Mensajes de confirmación
    'confirmation' => [
        'delete' => '¿Estás seguro de que deseas eliminar este :item?',
        'delete_multiple' => '¿Estás seguro de que deseas eliminar los elementos seleccionados?',
        'deactivate' => '¿Estás seguro de que deseas desactivar este :item?',
        'activate' => '¿Estás seguro de que deseas activar este :item?',
        'restore' => '¿Estás seguro de que deseas restaurar este :item?',
        'permanent_delete' => '¿Estás seguro de que deseas eliminar permanentemente este :item? Esta acción no se puede deshacer.',
        'discard_changes' => '¿Estás seguro de que deseas descartar los cambios?',
        'leave_page' => 'Tienes cambios sin guardar. ¿Estás seguro de que deseas salir?',
        'action_irreversible' => 'Esta acción no se puede deshacer.',
        'continue_action' => '¿Deseas continuar?',
    ],

    // Mensajes de notificación
    'notification' => [
        'new_message' => 'Tienes un nuevo mensaje.',
        'new_appointment' => 'Nueva cita agendada.',
        'appointment_reminder' => 'Recordatorio: Tienes una cita en :time.',
        'appointment_cancelled' => 'Tu cita ha sido cancelada.',
        'appointment_confirmed' => 'Tu cita ha sido confirmada.',
        'system_maintenance' => 'El sistema estará en mantenimiento de :start a :end.',
        'update_available' => 'Hay una actualización disponible.',
        'backup_completed' => 'Respaldo completado exitosamente.',
        'data_exported' => 'Los datos han sido exportados exitosamente.',
        'email_sent' => 'Correo electrónico enviado exitosamente.',
        'password_changed' => 'Tu contraseña ha sido cambiada exitosamente.',
    ],

    // Mensajes específicos de barberos
    'barber' => [
        'created' => 'Barbero creado exitosamente.',
        'updated' => 'Barbero actualizado exitosamente.',
        'deleted' => 'Barbero eliminado exitosamente.',
        'deactivated' => 'Barbero dado de baja exitosamente.',
        'reactivated' => 'Barbero reactivado exitosamente.',
        'permanently_deleted' => 'Barbero eliminado permanentemente del sistema.',
        'not_found' => 'Barbero no encontrado o inactivo.',
        'create_error' => 'Error al crear el barbero: :error',
        'update_error' => 'Error al actualizar el barbero: :error',
        'deactivate_error' => 'Error al dar de baja el barbero: :error',
        'reactivate_error' => 'Error al reactivar el barbero: :error',
        'delete_error' => 'Error al eliminar el barbero: :error',
    ],

    // Mensajes específicos de citas
    'appointment' => [
        'created' => 'Cita agendada exitosamente.',
        'updated' => 'Cita actualizada exitosamente.',
        'deleted' => 'Cita eliminada exitosamente.',
        'cancelled' => 'Cita cancelada exitosamente.',
        'attended' => 'Cita marcada como atendida exitosamente.',
        'max_appointments' => 'Ya existen 2 citas pendientes, no puedes agendar una tercera cita.',
        'no_availability' => 'Sin disponibilidad, asegúrate de haber elegido alguno de los horarios disponibles.',
        'cannot_attend' => 'Esta cita no puede ser marcada como atendida.',
        'past_appointment' => 'No puedes agendar una cita en el pasado.',
        'duplicate_appointment' => 'Ya tienes una cita agendada para esta fecha y hora.',
        'barber_unavailable' => 'El barbero no está disponible en el horario seleccionado.',
    ],

    // Mensajes específicos de servicios
    'service' => [
        'created' => 'Servicio creado exitosamente.',
        'updated' => 'Servicio actualizado exitosamente.',
        'deleted' => 'Servicio eliminado exitosamente.',
        'not_found' => 'Servicio no encontrado.',
        'in_use' => 'No se puede eliminar el servicio porque está siendo utilizado.',
    ],

    // Mensajes específicos de usuarios
    'user' => [
        'created' => 'Usuario creado exitosamente.',
        'updated' => 'Usuario actualizado exitosamente.',
        'deleted' => 'Usuario eliminado exitosamente.',
        'activated' => 'Usuario activado exitosamente.',
        'deactivated' => 'Usuario desactivado exitosamente.',
        'profile_updated' => 'Perfil actualizado exitosamente.',
        'password_updated' => 'Contraseña actualizada exitosamente.',
        'email_verified' => 'Correo electrónico verificado exitosamente.',
        'not_found' => 'Usuario no encontrado.',
        'already_exists' => 'Ya existe un usuario con este correo electrónico.',
    ],

    // Mensajes específicos de galería
    'gallery' => [
        'image_uploaded' => 'Imagen subida exitosamente.',
        'images_uploaded' => ':count imágenes subidas exitosamente.',
        'image_updated' => 'Imagen actualizada exitosamente.',
        'image_deleted' => 'Imagen eliminada exitosamente.',
        'images_deleted' => ':count imágenes eliminadas exitosamente.',
        'order_updated' => 'Orden de imágenes actualizado exitosamente.',
        'upload_failed' => 'Error al subir la imagen.',
        'invalid_format' => 'Formato de imagen no válido. Formatos soportados: JPG, PNG, GIF, WEBP.',
        'file_too_large' => 'La imagen es demasiado grande. Tamaño máximo: :size MB.',
        'no_images_selected' => 'No se han seleccionado imágenes.',
        'processing_images' => 'Procesando imágenes...',
        'upload_complete' => 'Subida completada.',
    ],

    // Mensajes de estado del sistema
    'system' => [
        'online' => 'Sistema en línea.',
        'offline' => 'Sistema fuera de línea.',
        'maintenance' => 'Sistema en mantenimiento.',
        'loading' => 'Cargando...',
        'processing' => 'Procesando...',
        'saving' => 'Guardando...',
        'deleting' => 'Eliminando...',
        'uploading' => 'Subiendo...',
        'downloading' => 'Descargando...',
        'connecting' => 'Conectando...',
        'disconnected' => 'Desconectado.',
        'reconnecting' => 'Reconectando...',
        'session_expired' => 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
        'unauthorized_access' => 'Acceso no autorizado detectado.',
    ],

    // Mensajes de validación personalizados
    'validation' => [
        'custom_required' => 'El campo :attribute es obligatorio.',
        'custom_email' => 'El campo :attribute debe ser un correo electrónico válido.',
        'custom_unique' => 'El :attribute ya está en uso.',
        'custom_min_length' => 'El :attribute debe tener al menos :min caracteres.',
        'custom_max_length' => 'El :attribute no puede tener más de :max caracteres.',
        'custom_numeric' => 'El :attribute debe ser un número.',
        'custom_date' => 'El :attribute debe ser una fecha válida.',
        'custom_time' => 'El :attribute debe ser una hora válida.',
        'custom_phone' => 'El :attribute debe ser un número de teléfono válido.',
        'custom_password' => 'La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números.',
        'passwords_match' => 'Las contraseñas no coinciden.',
        'current_password' => 'La contraseña actual es incorrecta.',
    ],

    // Mensajes de información
    'info' => [
        'no_data' => 'No hay datos disponibles.',
        'empty_list' => 'La lista está vacía.',
        'search_no_results' => 'No se encontraron resultados para tu búsqueda.',
        'filter_no_results' => 'No hay elementos que coincidan con los filtros aplicados.',
        'coming_soon' => 'Próximamente disponible.',
        'feature_disabled' => 'Esta funcionalidad está temporalmente deshabilitada.',
        'beta_feature' => 'Esta es una funcionalidad en versión beta.',
        'data_updated' => 'Los datos han sido actualizados.',
        'auto_save' => 'Guardado automático activado.',
        'changes_detected' => 'Se han detectado cambios.',
        'unsaved_changes' => 'Tienes cambios sin guardar.',
    ],

    // Mensajes de ayuda
    'help' => [
        'tooltip_edit' => 'Haz clic para editar.',
        'tooltip_delete' => 'Haz clic para eliminar.',
        'tooltip_view' => 'Haz clic para ver detalles.',
        'tooltip_download' => 'Haz clic para descargar.',
        'tooltip_upload' => 'Arrastra archivos aquí o haz clic para seleccionar.',
        'keyboard_shortcut' => 'Atajo de teclado: :shortcut',
        'required_fields' => 'Los campos marcados con * son obligatorios.',
        'optional_field' => 'Campo opcional.',
        'format_hint' => 'Formato esperado: :format',
        'example' => 'Ejemplo: :example',
    ],
];
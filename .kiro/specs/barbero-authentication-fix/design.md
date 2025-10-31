# Design Document

## Overview

Este diseño soluciona el problema de autenticación de barberos implementando un sistema unificado donde cada barbero tiene un usuario correspondiente en la tabla `users` de Laravel. El diseño incluye funcionalidades de activación/desactivación (soft delete) y eliminación permanente, manteniendo la integridad de datos y el historial de citas.

## Architecture

### Current State Problems
- Barberos se crean solo en tabla `barberos` con password local
- No se crean usuarios en tabla `users` para autenticación
- No se asignan roles de Spatie Permission
- Inconsistencia entre sistema de autenticación y datos de barberos

### Target State Solution
- Cada barbero tendrá un usuario correspondiente en tabla `users`
- Autenticación unificada a través de Laravel Auth
- Roles manejados por Spatie Permission
- Estados de barbero (activo/inactivo) para soft delete
- Sincronización automática entre tablas `users` y `barberos`

## Components and Interfaces

### 1. Database Schema Changes

#### Migration: Update Barberos Table
```sql
-- Eliminar campo password redundante de barberos
ALTER TABLE barberos DROP COLUMN password;

-- Hacer user_id obligatorio (no nullable)
ALTER TABLE barberos MODIFY user_id BIGINT UNSIGNED NOT NULL;

-- Agregar campo de estado
ALTER TABLE barberos ADD COLUMN activo BOOLEAN DEFAULT TRUE;
ALTER TABLE barberos ADD COLUMN fecha_baja TIMESTAMP NULL;
```

#### Migration: Ensure Users Table Integrity
- Verificar que todos los barberos existentes tengan usuarios correspondientes
- Crear usuarios faltantes con roles apropiados

### 2. Model Updates

#### Barbero Model Enhancements
```php
class Barbero extends Model
{
    protected $fillable = [
        'nombre_completo', 'email', 'telefono', 'especialidad', 
        'experiencia', 'foto', 'user_id', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_baja' => 'datetime'
    ];

    // Scopes para filtrar barberos activos/inactivos
    public function scopeActivos($query) {
        return $query->where('activo', true);
    }

    public function scopeInactivos($query) {
        return $query->where('activo', false);
    }
}
```

### 3. Controller Logic Redesign

#### AdminController::barberosStore()
1. Validar datos del barbero
2. Crear usuario en tabla `users` con email y password hasheada
3. Asignar rol "barbero" al usuario creado
4. Crear registro en tabla `barberos` vinculado al usuario
5. Manejar transacciones para consistencia

#### AdminController::barberosUpdate()
1. Actualizar datos en tabla `barberos`
2. Sincronizar cambios con tabla `users` (email, password si se proporciona)
3. Mantener consistencia entre ambas tablas

#### New Methods for Status Management
- `barberosDarDeBaja()`: Marcar barbero como inactivo
- `barberosReactivar()`: Reactivar barbero inactivo
- `barberosEliminarPermanente()`: Eliminación completa

## Data Models

### User-Barbero Relationship
```
users (1) ←→ (1) barberos
- users.id = barberos.user_id
- users.email = barberos.email (sincronizado)
- Cascade delete: eliminar user elimina barbero
```

### Authentication Flow
```
Login Request → Laravel Auth (users table) → Role Check → Dashboard Redirect
```

### Status Management
```
Barbero States:
- activo: true → Puede iniciar sesión
- activo: false → No puede iniciar sesión, datos preservados
- deleted → Eliminación permanente (cascade)
```

## Error Handling

### Authentication Errors
- **Invalid credentials**: Mensaje estándar de Laravel
- **Inactive barbero**: "Tu cuenta ha sido desactivada. Contacta al administrador."
- **Missing role**: "No tienes permisos para acceder a esta sección."

### Data Consistency Errors
- **Email conflicts**: Validación única en ambas tablas
- **Missing user**: Auto-creación durante actualización
- **Transaction failures**: Rollback completo con mensaje de error

### Validation Rules
```php
// Creación de barbero
'email' => 'required|email|unique:users,email|unique:barberos,email'
'password' => 'required|string|min:8|confirmed'

// Actualización de barbero
'email' => 'required|email|unique:users,email,'.$user_id.'|unique:barberos,email,'.$barbero_id
'password' => 'nullable|string|min:8|confirmed'
```

## Testing Strategy

### Unit Tests
- Barbero model relationships and scopes
- User creation and role assignment
- Email synchronization between tables
- Status change methods

### Integration Tests
- Complete barbero creation flow (Admin → User → Role)
- Authentication flow for barberos
- Status management (activate/deactivate)
- Data consistency during updates

### Feature Tests
- Admin can create barberos that can login immediately
- Inactive barberos cannot access the system
- Email changes sync between users and barberos tables
- Permanent deletion removes all related data

## Consideraciones de Implementación

### Estrategia de Migración de Datos
1. **Respaldar datos existentes** antes de ejecutar migraciones
2. **Crear usuarios faltantes** para barberos existentes
3. **Asignar roles** a todos los usuarios barbero
4. **Verificar integridad de datos** después de la migración

### Consideraciones de Rendimiento
- Usar transacciones de base de datos para operaciones multi-tabla
- Implementar eager loading para relaciones user-barbero
- Agregar índices de base de datos para campos consultados frecuentemente

### Consideraciones de Seguridad
- Hashear contraseñas usando Hash facade de Laravel
- Validar permisos antes de cambios de estado
- Sanitizar datos de entrada para prevenir XSS
- Usar protección CSRF en todos los formularios

### Compatibilidad Hacia Atrás
- Mantener endpoints de API existentes
- Preservar IDs de barberos existentes y relaciones
- Mantener relaciones de citas intactas durante transiciones
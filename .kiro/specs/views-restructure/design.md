# Documento de Diseño - Reestructuración de Vistas por Roles

## Resumen

Este diseño detalla la reestructuración del directorio de vistas de Laravel para organizar las plantillas Blade según los tres roles de usuario: admin, barbero y usuario. El enfoque principal es mover archivos existentes a una estructura más lógica y mantener todas las referencias actualizadas.

## Arquitectura

### Estructura Actual vs Propuesta

#### Estructura Actual
```
resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── barberos/
│   └── servicios/
├── worker/
│   ├── dashboard.blade.php
│   └── appointment-manager.blade.php
├── user/
│   ├── agendar-cita.blade.php
│   ├── detalle-cita.blade.php
│   └── user-elements/
├── barberos/ (vistas CRUD mixtas)
├── servicios/ (vistas CRUD mixtas)
├── citas/ (vistas CRUD mixtas)
└── [otros directorios compartidos]
```

#### Estructura Propuesta
```
resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── barberos/
│   ├── servicios/
│   └── citas/
├── barbero/
│   ├── dashboard.blade.php
│   ├── citas/
│   └── perfil/
├── usuario/
│   ├── dashboard.blade.php
│   ├── citas/
│   ├── perfil/
│   └── publico/
├── components/ (compartidos)
├── layouts/ (compartidos)
├── auth/ (compartidos)
└── profile/ (compartidos)
```

## Componentes y Interfaces

### 1. Mapeo de Archivos por Rol

#### Vistas de Admin
- **Origen**: `admin/` (mantener)
- **Destino**: `admin/` (reorganizar contenido)
- **Archivos adicionales a mover**:
  - `barberos/` → `admin/barberos/`
  - `servicios/` → `admin/servicios/`
  - Vistas de gestión de `citas/` → `admin/citas/`

#### Vistas de Barbero
- **Origen**: `worker/`
- **Destino**: `barbero/`
- **Archivos a mover**:
  - `worker/dashboard.blade.php` → `barbero/dashboard.blade.php`
  - `worker/appointment-manager.blade.php` → `barbero/citas/index.blade.php`

#### Vistas de Usuario
- **Origen**: `user/`
- **Destino**: `usuario/`
- **Archivos a mover**:
  - `user/agendar-cita.blade.php` → `usuario/citas/create.blade.php`
  - `user/detalle-cita.blade.php` → `usuario/citas/show.blade.php`
  - `user/user-elements/` → `usuario/publico/`

### 2. Componentes Compartidos

#### Componentes Existentes (mantener)
- `components/` - Componentes UI generales
- `layouts/` - Layouts base
- `auth/` - Vistas de autenticación
- `profile/` - Gestión de perfil

#### Nuevos Componentes por Rol
```
components/
├── admin/
│   ├── sidebar.blade.php
│   └── stats-card.blade.php
├── barbero/
│   ├── appointment-card.blade.php
│   └── schedule-widget.blade.php
├── usuario/
│   ├── service-card.blade.php
│   └── booking-form.blade.php
└── shared/ (componentes existentes)
```

## Modelos de Datos

### Referencias de Vista Afectadas

#### Controladores que Requieren Actualización
1. **AdminController**
   - Rutas: `admin.*`
   - Vistas: `admin/dashboard`, `admin/barberos.*`, `admin/servicios.*`

2. **BarberoController**
   - Rutas: `barbero.*`
   - Vistas: `barbero/dashboard`, `barbero/citas.*`

3. **CitaController** (usuario)
   - Rutas: sin prefijo (usuario regular)
   - Vistas: `usuario/citas.*`

#### Mapeo de Rutas y Vistas
```php
// Admin
'admin.dashboard' => 'admin.dashboard'
'admin.barberos.index' => 'admin.barberos.index'
'admin.servicios.index' => 'admin.servicios.index'

// Barbero
'barbero.dashboard' => 'barbero.dashboard'
'barbero.citas.index' => 'barbero.citas.index'

// Usuario
'citas.create' => 'usuario.citas.create'
'citas.show' => 'usuario.citas.show'
```

## Manejo de Errores

### Validación de Referencias
1. **Verificación de Rutas**: Asegurar que todas las rutas apunten a las vistas correctas
2. **Validación de Includes**: Verificar que todos los `@include` y `@extends` sean actualizados
3. **Componentes**: Confirmar que las referencias a componentes sean válidas

### Estrategia de Rollback
1. **Backup**: Crear respaldo del directorio `resources/views/` antes de iniciar
2. **Versionado**: Usar Git para trackear todos los cambios
3. **Pruebas**: Validar cada rol después de mover sus vistas

## Estrategia de Testing

### Pruebas de Integración
1. **Pruebas por Rol**:
   - Admin: Verificar acceso a todas las vistas administrativas
   - Barbero: Confirmar funcionalidad de gestión de citas
   - Usuario: Validar proceso de reserva y visualización

2. **Pruebas de Navegación**:
   - Verificar que todos los enlaces internos funcionen
   - Confirmar que los layouts se rendericen correctamente
   - Validar que los componentes se carguen apropiadamente

### Validación Manual
1. **Autenticación**: Probar login con cada tipo de usuario
2. **Navegación**: Verificar menús y enlaces por rol
3. **Funcionalidad**: Confirmar que todas las acciones CRUD funcionen

## Plan de Migración

### Fase 1: Preparación
1. Crear estructura de directorios nueva
2. Identificar todas las referencias de vista en controladores
3. Mapear dependencias entre vistas

### Fase 2: Movimiento de Archivos
1. Mover vistas de admin (ya organizadas)
2. Mover vistas de barbero desde `worker/`
3. Mover vistas de usuario desde `user/`
4. Reorganizar vistas CRUD mixtas

### Fase 3: Actualización de Referencias
1. Actualizar controladores
2. Actualizar referencias en vistas (includes, extends)
3. Actualizar rutas si es necesario

### Fase 4: Validación
1. Probar cada rol individualmente
2. Verificar funcionalidad completa
3. Confirmar que no hay enlaces rotos

## Consideraciones Técnicas

### Convenciones de Nomenclatura
- **Directorios**: Usar nombres en español que coincidan con los roles (`admin`, `barbero`, `usuario`)
- **Archivos**: Mantener nomenclatura Laravel estándar (`index.blade.php`, `create.blade.php`, etc.)
- **Componentes**: Organizar por funcionalidad y rol

### Compatibilidad
- **Laravel**: Mantener compatibilidad con la estructura de vistas de Laravel
- **Blade**: Asegurar que todas las directivas Blade funcionen correctamente
- **Rutas**: Minimizar cambios en el archivo de rutas existente

### Performance
- **Carga**: No impacto en performance, solo reorganización
- **Cache**: Limpiar cache de vistas después de la migración
- **Autoload**: Verificar que el autoload de componentes funcione
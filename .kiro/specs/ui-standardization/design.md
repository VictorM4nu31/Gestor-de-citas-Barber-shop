# Design Document - UI Standardization

## Overview

Este documento describe el diseño para estandarizar la interfaz de usuario de la aplicación de barbería, implementando una paleta de colores consistente y creando un sistema de componentes reutilizables. El diseño se basa en la estructura existente de Laravel Blade y Tailwind CSS, actualizando la configuración de colores y creando componentes que reemplacen el código duplicado actual.

## Architecture

### Color System Architecture

La aplicación utilizará un sistema de colores centralizado basado en la paleta definida, reemplazando los colores actuales en `tailwind.config.js`:

**Colores Actuales vs Nuevos:**
- `primary: '#dc2626'` → `primary: '#B71C1C'`
- `secondary: '#111827'` → `secondary: '#1C1C1C'` 
- `accent: '#9ca3af'` → `accent: '#C0C0C0'`
- `background: '#ffffff'` → `background: '#FFFFFF'` (sin cambio)
- `surface: '#f3f4f6'` → `surface: '#D3D3D3'`
- `muted: '#6b7280'` → `muted: '#9E9E9E'`
- `danger: '#dc2626'` → `danger: '#E53935'`
- `success: '#16a34a'` → `success: '#43A047'`
- `warning: '#f59e0b'` → `warning: '#FDD835'`
- `info: '#2563eb'` → `info: '#1E88E5'`

### Component Architecture

El sistema de componentes seguirá la estructura de Laravel Blade Components con la siguiente jerarquía:

```
resources/views/components/
├── ui/
│   ├── button.blade.php
│   ├── card.blade.php
│   ├── alert.blade.php
│   └── badge.blade.php
├── form/
│   ├── input.blade.php
│   ├── textarea.blade.php
│   ├── select.blade.php
│   ├── checkbox.blade.php
│   ├── label.blade.php
│   └── error.blade.php
└── layout/
    ├── header.blade.php
    └── logo.blade.php
```

## Components and Interfaces

### 1. UI Components

#### Button Component (`components/ui/button.blade.php`)
**Props:**
- `type`: 'primary', 'secondary', 'danger', 'success', 'warning', 'info'
- `size`: 'sm', 'md', 'lg'
- `href`: Para botones tipo enlace
- `disabled`: Boolean

**Variants:**
- Primary: `bg-primary hover:bg-primary/90 text-white`
- Secondary: `bg-secondary hover:bg-secondary/90 text-white`
- Danger: `bg-danger hover:bg-danger/90 text-white`

#### Card Component (`components/ui/card.blade.php`)
**Props:**
- `padding`: 'sm', 'md', 'lg'
- `shadow`: Boolean
- `border`: Boolean

**Styling:** `bg-surface border border-accent rounded-lg`

#### Alert Component (`components/ui/alert.blade.php`)
**Props:**
- `type`: 'success', 'danger', 'warning', 'info'
- `dismissible`: Boolean

**Variants:**
- Success: `bg-success/10 border-success text-success`
- Danger: `bg-danger/10 border-danger text-danger`
- Warning: `bg-warning/10 border-warning text-warning`
- Info: `bg-info/10 border-info text-info`

#### Badge Component (`components/ui/badge.blade.php`)
**Props:**
- `type`: 'success', 'danger', 'warning', 'info', 'muted'
- `size`: 'sm', 'md'

### 2. Form Components

#### Input Component (`components/form/input.blade.php`)
**Props:**
- `name`: Campo name
- `type`: 'text', 'email', 'password', 'number', 'date', 'time'
- `label`: Texto del label
- `required`: Boolean
- `placeholder`: Texto placeholder
- `value`: Valor por defecto

**Styling:** `border border-accent rounded-md focus:border-primary focus:ring-primary`

#### Select Component (`components/form/select.blade.php`)
**Props:**
- `name`: Campo name
- `label`: Texto del label
- `options`: Array de opciones
- `required`: Boolean
- `placeholder`: Opción por defecto

#### Textarea Component (`components/form/textarea.blade.php`)
**Props:**
- `name`: Campo name
- `label`: Texto del label
- `rows`: Número de filas
- `required`: Boolean

#### Checkbox Component (`components/form/checkbox.blade.php`)
**Props:**
- `name`: Campo name
- `label`: Texto del label
- `value`: Valor del checkbox
- `checked`: Boolean

#### Label Component (`components/form/label.blade.php`)
**Props:**
- `for`: ID del campo asociado
- `required`: Boolean (muestra asterisco)

**Styling:** `text-sm font-medium text-secondary`

#### Error Component (`components/form/error.blade.php`)
**Props:**
- `field`: Nombre del campo para mostrar errores

**Styling:** `text-danger text-sm mt-1`

### 3. Layout Components

#### Header Component (`components/layout/header.blade.php`)
**Props:**
- `title`: Título de la página

**Features:**
- Logo integrado
- Navegación consistente
- Colores primary y secondary

#### Logo Component (`components/layout/logo.blade.php`)
**Props:**
- `size`: 'sm', 'md', 'lg'
- `class`: Clases adicionales

**Features:**
- Imagen responsive
- Enlace al dashboard apropiado según rol
- Alt text apropiado

## Data Models

### Component Props Interface

```php
// Button Component
interface ButtonProps {
    string $type = 'primary';
    string $size = 'md';
    ?string $href = null;
    bool $disabled = false;
    string $class = '';
}

// Form Input Component  
interface InputProps {
    string $name;
    string $type = 'text';
    ?string $label = null;
    bool $required = false;
    ?string $placeholder = null;
    mixed $value = null;
    string $class = '';
}

// Alert Component
interface AlertProps {
    string $type = 'info';
    bool $dismissible = false;
    string $class = '';
}
```

### Page Title Configuration

```php
// Configuración de títulos por ruta
$pageTitles = [
    'admin.dashboard' => 'Panel Administrativo - Barbería',
    'barbero.dashboard' => 'Panel Barbero - Barbería',
    'citas.create' => 'Agendar Cita - Barbería',
    'citas.index' => 'Mis Citas - Barbería',
    'admin.barberos.index' => 'Gestión de Barberos - Barbería',
    'admin.barberos.create' => 'Crear Barbero - Barbería',
    'admin.barberos.edit' => 'Editar Barbero - Barbería',
    'barbero.citas.index' => 'Mis Citas - Barbería',
    'barbero.citas.show' => 'Detalle de Cita - Barbería',
];
```

## Error Handling

### Validation Error Display
- Usar componente `<x-form.error>` para mostrar errores de validación
- Colores consistentes usando `text-danger`
- Posicionamiento estándar debajo de cada campo

### Alert Messages
- Usar componente `<x-ui.alert>` para mensajes del sistema
- Tipos: success, danger, warning, info
- Auto-dismiss opcional para mensajes temporales

### Form State Management
- Estados de loading en botones
- Validación en tiempo real opcional
- Preservar datos del formulario en caso de error

## Testing Strategy

### Component Testing
- Pruebas unitarias para cada componente Blade
- Verificar renderizado correcto con diferentes props
- Validar aplicación correcta de clases CSS

### Integration Testing
- Pruebas de formularios completos
- Verificar funcionamiento de validación
- Comprobar navegación y enlaces

### Visual Regression Testing
- Capturas de pantalla de componentes
- Verificar consistencia visual
- Comprobar responsive design

### Accessibility Testing
- Verificar contraste de colores
- Comprobar navegación por teclado
- Validar etiquetas y ARIA attributes

## Implementation Phases

### Phase 1: Color System Update
1. Actualizar `tailwind.config.js` con nueva paleta
2. Regenerar CSS compilado
3. Verificar que no se rompan estilos existentes

### Phase 2: Core UI Components
1. Crear componentes Button, Card, Alert, Badge
2. Implementar variantes y props
3. Documentar uso de cada componente

### Phase 3: Form Components
1. Crear componentes de formulario
2. Integrar con validación de Laravel
3. Implementar estados de error

### Phase 4: Layout Components
1. Crear componente Logo
2. Actualizar Header con logo
3. Implementar títulos de página dinámicos

### Phase 5: Migration and Cleanup
1. Reemplazar código existente con componentes
2. Actualizar todas las vistas
3. Eliminar CSS duplicado
4. Verificar funcionamiento completo

## Design Decisions and Rationales

### Color Palette Choice
- **Primary (#B71C1C)**: Rojo intenso que transmite profesionalismo y confianza
- **Secondary (#1C1C1C)**: Negro casi puro para contraste y elegancia
- **Accent (#C0C0C0)**: Plata para detalles metálicos, evoca herramientas de barbería
- **Surface (#D3D3D3)**: Gris claro para contenedores sin ser demasiado contrastante

### Component Architecture
- **Blade Components**: Aprovecha el sistema nativo de Laravel para mejor integración
- **Props-based**: Flexibilidad sin sacrificar consistencia
- **Atomic Design**: Componentes pequeños y reutilizables que se combinan

### Responsive Strategy
- **Mobile-first**: Diseño que funciona primero en móviles
- **Tailwind breakpoints**: Uso de sm:, md:, lg: para adaptabilidad
- **Flexible layouts**: Grid y flexbox para diferentes tamaños de pantalla

### Performance Considerations
- **CSS purging**: Tailwind elimina clases no utilizadas
- **Component caching**: Laravel cachea componentes compilados
- **Minimal JavaScript**: Solo lo necesario para interactividad

### Accessibility Standards
- **WCAG 2.1 AA**: Cumplimiento con estándares de accesibilidad
- **Color contrast**: Ratios mínimos de 4.5:1 para texto normal
- **Keyboard navigation**: Todos los elementos interactivos accesibles por teclado
- **Screen readers**: Etiquetas y ARIA attributes apropiados
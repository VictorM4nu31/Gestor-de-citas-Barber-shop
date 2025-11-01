# Design Document - Admin Photo Gallery

## Overview

Esta funcionalidad permitirá a los administradores gestionar una galería de fotos que se mostrará en la página de bienvenida. El sistema se integrará con la arquitectura existente de Laravel, utilizando el sistema de roles de Spatie Permission y el almacenamiento público ya configurado.

La galería será responsive y se mostrará entre las secciones existentes de la página de bienvenida, proporcionando una experiencia visual atractiva para los visitantes.

## Architecture

### Database Layer
- **Modelo GalleryImage**: Nuevo modelo Eloquent para gestionar las imágenes de la galería
- **Migración**: Nueva tabla `gallery_images` con campos para metadatos de imagen
- **Relaciones**: Sin relaciones complejas, modelo independiente

### Application Layer
- **GalleryController**: Controlador dedicado para la gestión de la galería
- **Integración AdminController**: Extensión del controlador existente para incluir rutas de galería
- **Validación**: Request classes para validar subida de imágenes
- **Servicio de Imágenes**: Clase de servicio para procesamiento de imágenes y miniaturas

### Presentation Layer
- **Componentes Blade Reutilizables**: Sistema modular de componentes para UI
- **Componentes de Administración**: Componentes específicos para gestión de galería
- **Componentes de Usuario**: Componentes para visualización pública de la galería
- **JavaScript Modular**: Módulos JS organizados por funcionalidad

## Components and Interfaces

### 1. Database Schema

```sql
-- Migration: create_gallery_images_table
CREATE TABLE gallery_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    path VARCHAR(500) NOT NULL,
    thumbnail_path VARCHAR(500) NOT NULL,
    size INTEGER UNSIGNED NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    alt_text VARCHAR(255) NULL,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_active_order (is_active, display_order),
    INDEX idx_created_at (created_at)
);
```

### 2. Model Structure

```php
// app/Models/GalleryImage.php
class GalleryImage extends Model
{
    protected $fillable = [
        'filename', 'original_name', 'path', 'thumbnail_path',
        'size', 'mime_type', 'alt_text', 'display_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'size' => 'integer',
        'display_order' => 'integer'
    ];

    // Scopes para consultas comunes
    public function scopeActive($query)
    public function scopeOrdered($query)
    
    // Accessors para URLs
    public function getImageUrlAttribute()
    public function getThumbnailUrlAttribute()
}
```

### 3. Controller Architecture

```php
// app/Http/Controllers/Admin/GalleryController.php
class GalleryController extends Controller
{
    public function index()           // Lista de imágenes para admin
    public function create()          // Formulario de subida
    public function store(Request)    // Procesar subida múltiple
    public function show(GalleryImage) // Ver imagen individual
    public function edit(GalleryImage) // Editar metadatos
    public function update(Request, GalleryImage) // Actualizar
    public function destroy(GalleryImage) // Eliminar imagen
    public function reorder(Request)  // Reordenar imágenes
}

// Extensión de AdminController existente
// Agregar métodos para integrar galería en dashboard
```

### 4. Request Validation Classes

```php
// app/Http/Requests/StoreGalleryImageRequest.php
class StoreGalleryImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,webp|max:5120',
            'alt_texts' => 'nullable|array',
            'alt_texts.*' => 'nullable|string|max:255'
        ];
    }
}

// app/Http/Requests/UpdateGalleryImageRequest.php
class UpdateGalleryImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'alt_text' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ];
    }
}
```

### 5. Image Processing Service

```php
// app/Services/ImageProcessingService.php
class ImageProcessingService
{
    public function processGalleryImages(array $images): array
    public function createThumbnail(string $imagePath): string
    public function generateUniqueFilename(string $originalName): string
    public function deleteImageFiles(GalleryImage $image): bool
    
    private function resizeImage(string $path, int $width, int $height): void
    private function optimizeImage(string $path): void
}
```

## Data Models

### GalleryImage Model Attributes

- **id**: Primary key
- **filename**: Nombre único del archivo generado por el sistema
- **original_name**: Nombre original del archivo subido
- **path**: Ruta completa del archivo en storage/app/public/gallery
- **thumbnail_path**: Ruta de la miniatura en storage/app/public/gallery/thumbnails
- **size**: Tamaño del archivo en bytes
- **mime_type**: Tipo MIME del archivo (image/jpeg, image/png, image/webp)
- **alt_text**: Texto alternativo para accesibilidad (opcional)
- **display_order**: Orden de visualización (0 = primero)
- **is_active**: Estado activo/inactivo de la imagen
- **created_at/updated_at**: Timestamps de Laravel

### File Storage Structure

```
storage/app/public/
├── gallery/
│   ├── [unique_filename_1].jpg
│   ├── [unique_filename_2].png
│   └── thumbnails/
│       ├── [unique_filename_1]_thumb.jpg
│       └── [unique_filename_2]_thumb.png
```

## Error Handling

### Validation Errors
- **Formato de archivo inválido**: Mensaje específico sobre formatos permitidos
- **Tamaño excedido**: Información clara sobre límite de 5MB
- **Límite de archivos**: Máximo 10 imágenes por subida
- **Archivo corrupto**: Validación de integridad de imagen

### Storage Errors
- **Espacio insuficiente**: Manejo de errores de almacenamiento
- **Permisos de escritura**: Verificación de permisos de directorio
- **Fallo en procesamiento**: Rollback de archivos parcialmente procesados

### Database Errors
- **Constraint violations**: Manejo de errores de base de datos
- **Transaction rollback**: Consistencia entre archivos y registros DB

### Error Response Format
```php
// Respuestas JSON para AJAX
{
    "success": false,
    "message": "Error descriptivo para el usuario",
    "errors": {
        "field": ["Detalle específico del error"]
    }
}

// Redirecciones con errores para formularios tradicionales
return redirect()->back()
    ->withInput()
    ->withErrors(['error' => 'Mensaje de error']);
```

## Testing Strategy

### Unit Tests
- **GalleryImage Model**: Pruebas de scopes, accessors y mutators
- **ImageProcessingService**: Pruebas de procesamiento y redimensionado
- **Request Validation**: Pruebas de reglas de validación

### Feature Tests
- **Gallery CRUD Operations**: Pruebas completas de creación, lectura, actualización y eliminación
- **File Upload Process**: Pruebas de subida múltiple y procesamiento
- **Authorization**: Verificación de permisos de administrador
- **Image Display**: Pruebas de visualización en welcome page

### Integration Tests
- **Admin Dashboard Integration**: Pruebas de integración con panel existente
- **Welcome Page Integration**: Verificación de renderizado correcto
- **File Storage Integration**: Pruebas de almacenamiento y recuperación

### Browser Tests (Opcional)
- **Upload Flow**: Pruebas end-to-end de subida de imágenes
- **Gallery Interaction**: Pruebas de modal y navegación
- **Responsive Behavior**: Verificación en diferentes dispositivos

## Component Architecture

### Admin Components Structure

```
resources/views/components/admin/gallery/
├── index.blade.php              # Componente principal de lista
├── upload-zone.blade.php        # Zona de drag & drop upload
├── image-grid.blade.php         # Grid de imágenes con miniaturas
├── image-card.blade.php         # Tarjeta individual de imagen
├── image-actions.blade.php      # Botones de acción (editar/eliminar)
├── upload-modal.blade.php       # Modal para subida múltiple
├── edit-modal.blade.php         # Modal para editar metadatos
└── reorder-interface.blade.php  # Interfaz de reordenamiento
```

### Public Components Structure

```
resources/views/components/gallery/
├── section.blade.php            # Sección completa para welcome page
├── grid.blade.php              # Grid responsive de imágenes
├── image-item.blade.php        # Item individual de imagen
├── lightbox.blade.php          # Modal lightbox para vista ampliada
├── navigation.blade.php        # Controles de navegación en lightbox
└── placeholder.blade.php       # Placeholder cuando no hay imágenes
```

### JavaScript Modules Structure

```
resources/js/components/
├── gallery/
│   ├── admin/
│   │   ├── upload-manager.js    # Gestión de subida de archivos
│   │   ├── image-reorder.js     # Funcionalidad drag & drop
│   │   ├── bulk-actions.js      # Acciones masivas
│   │   └── preview-modal.js     # Modal de vista previa
│   └── public/
│       ├── lightbox.js          # Lightbox para galería pública
│       ├── lazy-loading.js      # Carga diferida de imágenes
│       └── touch-gestures.js    # Gestos táctiles para móviles
```

## UI/UX Design Considerations

### Component-Based Admin Interface

#### 1. Gallery Index Component (`<x-admin.gallery.index>`)
- **Props**: `images`, `totalCount`, `currentPage`
- **Slots**: `header`, `actions`, `content`
- **Features**: Paginación, filtros, búsqueda
- **Responsive**: Grid adaptativo según tamaño de pantalla

#### 2. Upload Zone Component (`<x-admin.gallery.upload-zone>`)
- **Props**: `maxFiles`, `maxSize`, `acceptedTypes`
- **Events**: `onFilesSelected`, `onUploadProgress`, `onUploadComplete`
- **Features**: Drag & drop, preview de archivos, barra de progreso
- **Validation**: Validación en tiempo real del lado cliente

#### 3. Image Card Component (`<x-admin.gallery.image-card>`)
- **Props**: `image`, `showActions`, `selectable`
- **Slots**: `actions`, `overlay`
- **Features**: Checkbox de selección, acciones rápidas, vista previa
- **States**: Normal, seleccionado, cargando, error

#### 4. Reorder Interface Component (`<x-admin.gallery.reorder-interface>`)
- **Props**: `images`, `sortable`
- **Events**: `onReorder`, `onSave`
- **Features**: Drag & drop visual, indicadores de posición
- **Feedback**: Animaciones suaves, confirmación de cambios

### Component-Based Public Interface

#### 1. Gallery Section Component (`<x-gallery.section>`)
- **Props**: `title`, `images`, `layout`, `showCount`
- **Slots**: `header`, `footer`
- **Features**: Título configurable, contador de imágenes
- **Integration**: Se integra seamlessly en welcome page

#### 2. Gallery Grid Component (`<x-gallery.grid>`)
- **Props**: `images`, `columns`, `aspectRatio`, `lazyLoad`
- **Features**: Grid responsive, lazy loading, aspect ratio consistente
- **Responsive**: 1 columna (móvil), 2-3 (tablet), 4-6 (desktop)

#### 3. Image Item Component (`<x-gallery.image-item>`)
- **Props**: `image`, `clickable`, `showOverlay`
- **Events**: `onClick`, `onLoad`, `onError`
- **Features**: Overlay con información, loading states
- **Accessibility**: Alt text, keyboard navigation

#### 4. Lightbox Component (`<x-gallery.lightbox>`)
- **Props**: `images`, `currentIndex`, `showNavigation`
- **Events**: `onClose`, `onNext`, `onPrevious`
- **Features**: Navegación con teclado, gestos táctiles, zoom
- **Performance**: Preload de imágenes adyacentes

### Component Props and API Design

#### Admin Gallery Index Component
```php
<x-admin.gallery.index 
    :images="$images"
    :total-count="$totalCount"
    :current-page="$currentPage"
    :per-page="$perPage"
    upload-route="{{ route('admin.gallery.store') }}"
    reorder-route="{{ route('admin.gallery.reorder') }}"
>
    <x-slot:header>
        <h2>Gestión de Galería</h2>
        <x-admin.gallery.bulk-actions />
    </x-slot:header>
    
    <x-slot:actions>
        <x-admin.gallery.upload-button />
        <x-admin.gallery.settings-button />
    </x-slot:actions>
</x-admin.gallery.index>
```

#### Public Gallery Section Component
```php
<x-gallery.section 
    title="Nuestra Galería"
    :images="$galleryImages"
    layout="masonry"
    :columns="['mobile' => 1, 'tablet' => 2, 'desktop' => 3]"
    :lazy-load="true"
    :show-count="true"
>
    <x-slot:header>
        <p class="text-muted">Descubre nuestro trabajo y ambiente</p>
    </x-slot:header>
</x-gallery.section>
```

### Performance Considerations
- **Component Caching**: Cache de componentes Blade para mejor rendimiento
- **Lazy Component Loading**: Carga diferida de componentes pesados
- **Asset Bundling**: Agrupación inteligente de CSS/JS por componente
- **Image Optimization**: Componentes optimizados para diferentes tamaños

## Security Considerations

### File Upload Security
- **Validación de tipo MIME**: Verificación real del contenido del archivo
- **Sanitización de nombres**: Limpieza de nombres de archivo
- **Límites de tamaño**: Prevención de ataques de agotamiento de espacio
- **Directorio seguro**: Almacenamiento fuera del document root público

### Access Control
- **Middleware de autenticación**: Verificación de login para admin
- **Middleware de autorización**: Verificación de rol de administrador
- **CSRF Protection**: Tokens CSRF en todos los formularios
- **Rate Limiting**: Límites en subida de archivos

### Data Validation
- **Input sanitization**: Limpieza de todos los inputs del usuario
- **SQL Injection Prevention**: Uso de Eloquent ORM y prepared statements
- **XSS Prevention**: Escape de output en vistas Blade
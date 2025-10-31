# Diseño - Consistencia de Servicios y Asignación por Barbero

## Resumen

Este diseño implementa un sistema de asignación de servicios por barbero y garantiza la consistencia en la visualización de servicios entre la vista principal y la vista de agendar citas. Los usuarios solo verán servicios que estén tanto publicados como asignados al barbero seleccionado.

## Arquitectura

### Modelo de Datos

#### Tabla Pivot: `barbero_servicio`
```sql
CREATE TABLE barbero_servicio (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    barbero_id BIGINT UNSIGNED NOT NULL,
    servicio_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (barbero_id) REFERENCES barberos(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_barbero_servicio (barbero_id, servicio_id)
);
```

#### Relaciones Eloquent

**Modelo Barbero:**
```php
public function servicios()
{
    return $this->belongsToMany(Servicio::class, 'barbero_servicio');
}

public function serviciosPublicados()
{
    return $this->belongsToMany(Servicio::class, 'barbero_servicio')
                ->where('publicado', true)
                ->orderBy('orden', 'asc')
                ->orderBy('created_at', 'desc');
}
```

**Modelo Servicio:**
```php
public function barberos()
{
    return $this->belongsToMany(Barbero::class, 'barbero_servicio');
}
```

### Flujo de Datos

#### Vista Principal (Welcome)
1. Obtener todos los servicios publicados usando `Servicio::publicadosOrdenados()`
2. Mostrar servicios sin filtro por barbero (vista general)
3. Mantener la funcionalidad actual para admins (preview de todos los servicios)

#### Vista Agendar Citas
1. **Carga inicial**: Mostrar todos los servicios publicados
2. **Selección de barbero**: Filtrar servicios dinámicamente via AJAX
3. **Validación**: Verificar que servicios seleccionados estén asignados al barbero

## Componentes y Interfaces

### Controladores

#### CitaController
```php
public function create()
{
    $servicios = Servicio::publicadosOrdenados()->get();
    $barberos = Barbero::activos()->get();
    return view('usuario.citas.create', compact('servicios', 'barberos'));
}

public function getServiciosByBarbero(Request $request)
{
    $barberoId = $request->input('barbero_id');
    $barbero = Barbero::findOrFail($barberoId);
    $servicios = $barbero->serviciosPublicados()->get();
    
    return response()->json($servicios);
}
```

#### AdminController
```php
public function barberosCreate()
{
    $servicios = Servicio::publicadosOrdenados()->get();
    return view('admin.barberos.create', compact('servicios'));
}

public function barberosStore(StoreRequest $request)
{
    // ... crear barbero
    $barbero->servicios()->sync($request->input('servicios', []));
    // ...
}

public function barberosEdit(Barbero $barbero)
{
    $servicios = Servicio::publicadosOrdenados()->get();
    $serviciosAsignados = $barbero->servicios->pluck('id')->toArray();
    return view('admin.barberos.edit', compact('barbero', 'servicios', 'serviciosAsignados'));
}
```

### Vistas

#### Formulario Admin - Crear/Editar Barbero
```blade
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Servicios que ofrece</label>
    <div class="mt-2 space-y-2">
        @foreach($servicios as $servicio)
            <div class="flex items-center">
                <input type="checkbox" 
                       name="servicios[]" 
                       value="{{ $servicio->id }}"
                       id="servicio_{{ $servicio->id }}"
                       @if(in_array($servicio->id, $serviciosAsignados ?? [])) checked @endif
                       class="mr-2">
                <label for="servicio_{{ $servicio->id }}" class="text-sm">
                    {{ $servicio->nombre }} - ${{ $servicio->precio }}
                </label>
            </div>
        @endforeach
    </div>
</div>
```

#### Vista Agendar Citas - JavaScript
```javascript
document.getElementById('id_barbero').addEventListener('change', function() {
    const barberoId = this.value;
    
    if (barberoId) {
        fetch(`/citas/servicios-barbero/${barberoId}`)
            .then(response => response.json())
            .then(servicios => {
                updateServiciosCheckboxes(servicios);
                updateCostoTotal();
            });
    } else {
        // Mostrar todos los servicios publicados
        resetServiciosCheckboxes();
    }
});

function updateServiciosCheckboxes(serviciosDisponibles) {
    const checkboxes = document.querySelectorAll('input[name="servicios[]"]');
    
    checkboxes.forEach(checkbox => {
        const servicioId = parseInt(checkbox.value);
        const disponible = serviciosDisponibles.some(s => s.id === servicioId);
        
        checkbox.disabled = !disponible;
        checkbox.checked = false; // Limpiar selecciones previas
        
        const label = checkbox.nextElementSibling;
        label.style.opacity = disponible ? '1' : '0.5';
    });
}
```

## Manejo de Errores

### Validaciones

#### Request de Citas
```php
public function rules()
{
    return [
        // ... otras reglas
        'servicios' => 'required|array|min:1',
        'servicios.*' => [
            'exists:servicios,id',
            function ($attribute, $value, $fail) {
                $barberoId = request()->input('id_barbero');
                if ($barberoId) {
                    $barbero = Barbero::find($barberoId);
                    if ($barbero && !$barbero->servicios()->where('servicios.id', $value)->exists()) {
                        $fail('El servicio seleccionado no está disponible para este barbero.');
                    }
                }
            }
        ],
    ];
}
```

### Casos de Error
1. **Servicio no asignado al barbero**: Mostrar mensaje específico
2. **Servicio no publicado**: Filtrar automáticamente
3. **Barbero sin servicios**: Mostrar mensaje informativo
4. **Conflictos de datos**: Logs detallados para debugging

## Estrategia de Testing

### Tests Unitarios
- Relaciones Eloquent entre Barbero y Servicio
- Scopes de servicios publicados
- Validaciones de asignación de servicios

### Tests de Integración
- Flujo completo de creación de barbero con servicios
- Filtrado dinámico de servicios por barbero
- Validación de citas con servicios asignados

### Tests de Funcionalidad
- Consistencia entre vista principal y agendar citas
- Comportamiento con barberos sin servicios asignados
- Funcionalidad de admin para gestionar asignaciones

## Consideraciones de Rendimiento

### Optimizaciones
1. **Eager Loading**: Cargar servicios con barberos cuando sea necesario
2. **Caching**: Cache de servicios publicados por barbero
3. **Índices**: Índice compuesto en tabla pivot para consultas rápidas

### Consultas Optimizadas
```php
// En lugar de N+1 queries
$barberos = Barbero::with('serviciosPublicados')->get();

// Cache para servicios frecuentemente consultados
Cache::remember("barbero_{$id}_servicios", 3600, function() use ($barbero) {
    return $barbero->serviciosPublicados()->get();
});
```

## Migración de Datos Existentes

### Estrategia de Migración
1. **Crear tabla pivot** `barbero_servicio`
2. **Migrar datos existentes**: Asignar todos los servicios publicados a todos los barberos activos
3. **Actualizar controladores** para usar nuevas relaciones
4. **Actualizar vistas** con funcionalidad de filtrado
5. **Testing exhaustivo** antes de deployment

### Script de Migración
```php
// En la migración
public function up()
{
    // Crear tabla pivot
    Schema::create('barbero_servicio', function (Blueprint $table) {
        // ... definición de tabla
    });
    
    // Asignar todos los servicios publicados a todos los barberos activos
    $barberos = Barbero::where('activo', true)->get();
    $serviciosPublicados = Servicio::where('publicado', true)->pluck('id');
    
    foreach ($barberos as $barbero) {
        $barbero->servicios()->sync($serviciosPublicados);
    }
}
```
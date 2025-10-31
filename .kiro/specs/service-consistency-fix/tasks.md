# Plan de Implementación - Consistencia de Servicios

- [x] 1. Crear migración para tabla pivot barbero_servicio





  - Crear migración con tabla pivot para relación many-to-many entre barberos y servicios
  - Incluir claves foráneas con cascade delete y índice único compuesto
  - Agregar script de migración de datos existentes para asignar servicios a barberos activos
  - _Requisitos: 2.3_

- [x] 2. Actualizar modelos Eloquent con nuevas relaciones





  - [x] 2.1 Agregar relación servicios() en modelo Barbero


    - Implementar relación belongsToMany con tabla pivot barbero_servicio
    - Crear método serviciosPublicados() con filtro de publicación y ordenamiento
    - _Requisitos: 2.3, 2.4_
  
  - [x] 2.2 Agregar relación barberos() en modelo Servicio


    - Implementar relación belongsToMany inversa con tabla pivot barbero_servicio
    - _Requisitos: 2.3_

- [x] 3. Actualizar controlador de citas para usar servicios filtrados





  - [x] 3.1 Modificar método create() en CitaController


    - Cambiar Servicio::all() por Servicio::publicadosOrdenados()->get()
    - Mantener carga de barberos activos
    - _Requisitos: 1.2, 3.3_
  
  - [x] 3.2 Crear endpoint para obtener servicios por barbero


    - Implementar método getServiciosByBarbero() que retorne servicios asignados al barbero
    - Validar que barbero existe y está activo
    - Retornar JSON con servicios publicados del barbero
    - _Requisitos: 2.4_
  
  - [x] 3.3 Actualizar validación en StoreRequest de citas


    - Agregar validación personalizada para verificar que servicios están asignados al barbero
    - Mantener validaciones existentes de servicios publicados
    - _Requisitos: 2.5, 3.4_

- [x] 4. Actualizar vistas de administración de barberos





  - [x] 4.1 Modificar vista create de barberos


    - Agregar sección de selección múltiple de servicios
    - Mostrar servicios publicados con checkboxes
    - Incluir información de precio y duración por servicio
    - _Requisitos: 2.1_
  
  - [x] 4.2 Modificar vista edit de barberos


    - Agregar sección de servicios con servicios actuales pre-seleccionados
    - Permitir modificar asignación de servicios existente
    - _Requisitos: 2.2_
  
  - [x] 4.3 Actualizar controlador AdminController


    - Modificar barberosCreate() para cargar servicios publicados
    - Actualizar barberosStore() para sincronizar servicios seleccionados
    - Modificar barberosEdit() para cargar servicios asignados
    - Actualizar barberosUpdate() para sincronizar cambios en servicios
    - _Requisitos: 2.1, 2.2_

- [x] 5. Implementar filtrado dinámico en vista de agendar citas





  - [x] 5.1 Agregar JavaScript para filtrado de servicios


    - Implementar listener en select de barbero para filtrar servicios
    - Crear función para actualizar checkboxes de servicios disponibles
    - Deshabilitar servicios no disponibles y limpiar selecciones previas
    - _Requisitos: 2.4_
  
  - [x] 5.2 Actualizar cálculo de costo total

    - Modificar función de cálculo para considerar solo servicios habilitados
    - Recalcular automáticamente al cambiar barbero seleccionado
    - _Requisitos: 2.4_
  
  - [x] 5.3 Agregar ruta para endpoint de servicios por barbero

    - Crear ruta GET /citas/servicios-barbero/{barbero} 
    - Aplicar middleware de autenticación
    - _Requisitos: 2.4_

- [x] 6. Ejecutar migración y poblar datos iniciales





  - Ejecutar migración para crear tabla barbero_servicio
  - Verificar que datos existentes se migren correctamente
  - Asignar todos los servicios publicados a barberos activos como estado inicial
  - _Requisitos: 2.3_

- [ ]* 7. Crear tests para nueva funcionalidad
  - [ ]* 7.1 Tests unitarios para relaciones de modelos
    - Test de relación barbero->servicios()
    - Test de relación barbero->serviciosPublicados()
    - Test de relación servicio->barberos()
    - _Requisitos: 2.3_
  
  - [ ]* 7.2 Tests de integración para controladores
    - Test de filtrado de servicios por barbero en CitaController
    - Test de asignación de servicios en AdminController
    - Test de validación de servicios en StoreRequest
    - _Requisitos: 2.4, 2.5_
  
  - [ ]* 7.3 Tests de funcionalidad end-to-end
    - Test de consistencia entre vista principal y agendar citas
    - Test de creación de barbero con servicios asignados
    - Test de agendado de cita con servicios filtrados por barbero
    - _Requisitos: 1.1, 1.2, 2.1_
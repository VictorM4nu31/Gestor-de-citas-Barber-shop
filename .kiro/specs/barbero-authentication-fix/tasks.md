# Plan de Implementación

- [x] 1. Crear migración para limpiar y mejorar tabla barberos





  - Eliminar campo password redundante de tabla barberos
  - Hacer user_id obligatorio (NOT NULL)
  - Agregar campos activo (boolean) y fecha_baja (timestamp)
  - _Requisitos: 6.1, 6.2, 6.3, 6.4_

- [x] 2. Crear migración de datos para barberos existentes





  - Crear usuarios en tabla users para barberos existentes que no los tengan
  - Asignar rol "barbero" a todos los usuarios de barberos
  - Sincronizar emails entre tablas users y barberos
  - Verificar integridad de datos después de la migración
  - _Requisitos: 1.1, 1.2, 1.3, 6.4_

- [x] 3. Actualizar modelo Barbero





  - Agregar campos activo y fecha_baja al fillable
  - Implementar scopes scopeActivos() y scopeInactivos()
  - Agregar casts para campos boolean y datetime
  - Eliminar referencias al campo password eliminado
  - _Requisitos: 4.1, 4.2, 6.1_

- [x] 4. Refactorizar AdminController::barberosStore()





  - Implementar creación de usuario en tabla users con transacciones
  - Asignar rol "barbero" al usuario creado
  - Vincular barbero con user_id del usuario creado
  - Sincronizar email entre ambas tablas
  - Manejar errores y rollback en caso de falla
  - _Requisitos: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 5. Refactorizar AdminController::barberosUpdate()





  - Sincronizar cambios de email entre users y barberos
  - Actualizar contraseña en tabla users si se proporciona
  - Crear usuario automáticamente si no existe
  - Mantener consistencia de datos con transacciones
  - _Requisitos: 3.1, 3.2, 3.3, 3.4_

- [x] 6. Implementar funcionalidad de dar de baja barberos





  - Crear método barberosDarDeBaja() en AdminController
  - Marcar barbero como inactivo (activo = false)
  - Registrar fecha_baja
  - Desactivar usuario correspondiente si es necesario
  - _Requisitos: 4.1, 4.2, 4.4_

- [x] 7. Implementar funcionalidad de reactivar barberos





  - Crear método barberosReactivar() en AdminController
  - Marcar barbero como activo (activo = true)
  - Limpiar fecha_baja
  - Reactivar usuario correspondiente
  - _Requisitos: 4.5_

- [x] 8. Implementar eliminación permanente de barberos





  - Crear método barberosEliminarPermanente() en AdminController
  - Eliminar usuario de tabla users (cascade eliminará barbero)
  - Mostrar confirmación y advertencias antes de eliminar
  - Mantener integridad referencial con citas
  - _Requisitos: 5.1, 5.2, 5.3, 5.4, 5.5_

- [x] 9. Actualizar middleware de autenticación para barberos inactivos





  - Verificar que barberos inactivos no puedan acceder al sistema
  - Mostrar mensaje apropiado para cuentas desactivadas
  - Mantener funcionalidad existente para barberos activos
  - _Requisitos: 4.3, 2.3_

- [x] 10. Actualizar vistas del admin para gestión de estados





  - Agregar botones de dar de baja/reactivar en lista de barberos
  - Mostrar estado visual (activo/inactivo) en la interfaz
  - Implementar confirmaciones para eliminación permanente
  - Agregar filtros para ver barberos activos/inactivos
  - _Requisitos: 4.1, 4.5, 5.4, 5.5_

- [x] 11. Actualizar validaciones y reglas de negocio





  - Validar unicidad de email en ambas tablas (users y barberos)
  - Implementar validaciones de contraseña para creación y actualización
  - Agregar validaciones de estado para operaciones de barberos
  - _Requisitos: 1.5, 3.2_

- [ ]* 12. Crear tests para funcionalidad de barberos
  - Tests unitarios para modelo Barbero (scopes, relaciones)
  - Tests de integración para creación y actualización de barberos
  - Tests de feature para flujo completo de autenticación
  - Tests para gestión de estados (activar/desactivar/eliminar)
  - _Requisitos: 1.1, 2.1, 4.1, 5.1_

- [x] 13. Verificar y corregir rutas y middleware existentes





  - Asegurar que rutas de barbero usen middleware de roles correctamente
  - Verificar redirecciones después de login para barberos
  - Mantener compatibilidad con rutas existentes
  - _Requisitos: 2.2, 2.3_
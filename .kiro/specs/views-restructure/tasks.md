# Plan de Implementación - Reestructuración de Vistas por Roles

- [x] 1. Preparar estructura de directorios y crear backup





  - Crear backup del directorio resources/views/ actual
  - Crear nuevos directorios para la estructura propuesta (barbero/, usuario/citas/, usuario/publico/, etc.)
  - _Requerimientos: 1.1, 1.3_

- [x] 2. Reorganizar vistas del rol barbero




- [x] 2.1 Mover vistas de worker/ a barbero/


  - Mover worker/dashboard.blade.php a barbero/dashboard.blade.php
  - Mover worker/appointment-manager.blade.php a barbero/citas/index.blade.php
  - Eliminar directorio worker/ vacío
  - _Requerimientos: 3.1, 3.2_

- [x] 2.2 Actualizar referencias en controladores para vistas de barbero


  - Actualizar BarberoController para usar nuevas rutas de vista (barbero.dashboard, barbero.citas.index)
  - Verificar que las rutas con prefijo 'barbero.' apunten a las vistas correctas
  - _Requerimientos: 3.3, 3.5_

- [x] 3. Reorganizar vistas del rol usuario





- [x] 3.1 Mover vistas de user/ a usuario/


  - Mover user/agendar-cita.blade.php a usuario/citas/create.blade.php
  - Mover user/detalle-cita.blade.php a usuario/citas/show.blade.php
  - Mover user/user-elements/ a usuario/publico/
  - Eliminar directorio user/ vacío
  - _Requerimientos: 4.1, 4.2_

- [x] 3.2 Actualizar referencias en controladores para vistas de usuario


  - Actualizar CitaController para usar nuevas rutas de vista (usuario.citas.create, usuario.citas.show)
  - Verificar que las rutas sin prefijo (usuario regular) apunten a las vistas correctas
  - _Requerimientos: 4.3, 4.4_

- [x] 4. Reorganizar vistas CRUD mixtas hacia roles específicos





- [x] 4.1 Mover vistas de gestión administrativa


  - Mover barberos/ (CRUD completo) a admin/barberos/
  - Mover servicios/ (CRUD completo) a admin/servicios/
  - Crear admin/citas/ y mover vistas de gestión de citas desde citas/
  - _Requerimientos: 2.1, 2.2_

- [x] 4.2 Actualizar controladores administrativos


  - Actualizar AdminController para usar rutas admin.barberos.*, admin.servicios.*, admin.citas.*
  - Verificar que todas las rutas con prefijo 'admin.' funcionen correctamente
  - _Requerimientos: 2.3, 2.4, 2.5_

- [x] 5. Actualizar referencias internas en vistas





- [x] 5.1 Actualizar includes y extends en vistas movidas


  - Revisar y actualizar todas las directivas @include en las vistas movidas
  - Revisar y actualizar todas las directivas @extends en las vistas movidas
  - Verificar referencias a componentes en las nuevas ubicaciones
  - _Requerimientos: 1.4, 5.3_

- [x] 5.2 Organizar componentes específicos por rol


  - Crear subdirectorios en components/ para admin/, barbero/, usuario/
  - Mover componentes específicos de rol a sus directorios correspondientes
  - Actualizar referencias a componentes en las vistas
  - _Requerimientos: 5.1, 5.2_

- [x] 6. Validar funcionalidad completa




- [x] 6.1 Probar acceso y funcionalidad por rol


  - Verificar que el login y dashboard de admin funcionen correctamente
  - Verificar que el login y dashboard de barbero funcionen correctamente
  - Verificar que las funciones de usuario (reserva de citas) funcionen correctamente
  - _Requerimientos: 2.4, 3.4, 4.4_

- [ ]* 6.2 Ejecutar pruebas automatizadas
  - Ejecutar pruebas existentes para verificar que no se rompió funcionalidad
  - Crear pruebas básicas para verificar que las vistas se rendericen correctamente
  - _Requerimientos: 1.5_

- [-] 7. Limpieza final y documentación


- [x] 7.1 Limpiar archivos y directorios obsoletos



  - Verificar que no queden directorios vacíos
  - Confirmar que todas las vistas fueron movidas correctamente
  - Limpiar cache de vistas de Laravel
  - _Requerimientos: 1.5_

- [ ]* 7.2 Actualizar documentación del proyecto
  - Documentar la nueva estructura de directorios
  - Actualizar README si es necesario con la nueva organización
  - _Requerimientos: 5.5_
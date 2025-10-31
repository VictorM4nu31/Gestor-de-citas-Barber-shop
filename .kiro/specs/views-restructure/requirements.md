# Documento de Requerimientos

## Introducción

Esta funcionalidad involucra la reestructuración del directorio de vistas de Laravel para organizar adecuadamente las vistas según los tres roles de usuario en el sistema de gestión de barbería: admin, barbero y usuario. La estructura actual tiene nomenclatura y organización inconsistente que no separa claramente la funcionalidad basada en roles.

## Glosario

- **Directorio_Vistas**: El directorio resources/views de Laravel que contiene todas las plantillas Blade
- **Rol_Admin**: Usuarios con privilegios administrativos que pueden gestionar todos los recursos del sistema
- **Rol_Barbero**: Usuarios barberos que pueden gestionar sus citas asignadas y ver servicios
- **Rol_Usuario**: Usuarios regulares que pueden crear y gestionar sus propias citas
- **Vistas_Por_Rol**: Vistas organizadas por rol de usuario con clara separación de responsabilidades
- **Plantillas_Blade**: Archivos del motor de plantillas de Laravel con extensión .blade.php

## Requerimientos

### Requerimiento 1

**Historia de Usuario:** Como desarrollador, quiero que el directorio de vistas esté organizado por roles de usuario, para poder localizar y mantener fácilmente las plantillas específicas de cada rol.

#### Criterios de Aceptación

1. EL Directorio_Vistas DEBERÁ contener subdirectorios separados para cada rol de usuario (admin, barbero, usuario)
2. CUANDO se organicen las vistas por rol, EL Directorio_Vistas DEBERÁ mantener convenciones de nomenclatura consistentes en todos los directorios de roles
3. EL Directorio_Vistas DEBERÁ preservar toda la funcionalidad existente mientras mueve y reorganiza los archivos existentes
4. EL Directorio_Vistas DEBERÁ incluir componentes compartidos y layouts en directorios comunes apropiados
5. CUANDO la reestructuración esté completa, EL Directorio_Vistas DEBERÁ mover todos los archivos existentes sin crear duplicados o archivos huérfanos

### Requerimiento 2

**Historia de Usuario:** Como usuario admin, quiero que todas las vistas administrativas estén en un directorio admin dedicado, para que la funcionalidad específica de admin esté claramente separada.

#### Criterios de Aceptación

1. LAS vistas del Rol_Admin DEBERÁN ser movidas al directorio resources/views/admin/ desde sus ubicaciones actuales
2. LAS vistas del Rol_Admin DEBERÁN incluir plantillas de dashboard, gestión de usuarios, gestión de barberos y gestión de servicios
3. CUANDO se acceda a las vistas de admin, LAS vistas del Rol_Admin DEBERÁN usar referencias consistentes de layout y componentes
4. LAS vistas del Rol_Admin DEBERÁN mantener toda la funcionalidad administrativa actual
5. LAS vistas del Rol_Admin DEBERÁN ser accesibles solo a través de rutas con prefijo admin

### Requerimiento 3

**Historia de Usuario:** Como usuario barbero, quiero que todas las vistas específicas de barbero estén en un directorio barbero dedicado, para poder acceder fácilmente a mi funcionalidad relacionada con el trabajo.

#### Criterios de Aceptación

1. LAS vistas del Rol_Barbero DEBERÁN ser movidas al directorio resources/views/barbero/ desde el directorio worker/ actual
2. LAS vistas del Rol_Barbero DEBERÁN incluir plantillas de dashboard y gestión de citas
3. CUANDO se acceda a las vistas de barbero, LAS vistas del Rol_Barbero DEBERÁN usar componentes apropiados de layout y navegación
4. LAS vistas del Rol_Barbero DEBERÁN mostrar solo las citas asignadas al barbero autenticado
5. LAS vistas del Rol_Barbero DEBERÁN ser accesibles solo a través de rutas con prefijo barbero

### Requerimiento 4

**Historia de Usuario:** Como usuario regular, quiero que todas las vistas específicas de usuario estén en un directorio usuario dedicado, para que la funcionalidad del cliente esté claramente organizada.

#### Criterios de Aceptación

1. LAS vistas del Rol_Usuario DEBERÁN ser movidas al directorio resources/views/usuario/ desde el directorio user/ actual
2. LAS vistas del Rol_Usuario DEBERÁN incluir plantillas de reserva de citas, detalles de citas y perfil de usuario
3. CUANDO se acceda a las vistas de usuario, LAS vistas del Rol_Usuario DEBERÁN usar layout y componentes orientados al cliente
4. LAS vistas del Rol_Usuario DEBERÁN permitir a los usuarios gestionar solo sus propias citas
5. LAS vistas del Rol_Usuario DEBERÁN incluir elementos públicos como galería y visualización de servicios

### Requerimiento 5

**Historia de Usuario:** Como desarrollador, quiero que los componentes compartidos y layouts estén adecuadamente organizados, para que la reutilización de código se mantenga en todos los roles.

#### Criterios de Aceptación

1. EL Directorio_Vistas DEBERÁ mantener componentes compartidos en el directorio resources/views/components/
2. EL Directorio_Vistas DEBERÁ organizar componentes específicos de rol en subdirectorios dentro de components/
3. CUANDO se usen layouts compartidos, EL Directorio_Vistas DEBERÁ mantener layouts en el directorio resources/views/layouts/
4. EL Directorio_Vistas DEBERÁ asegurar que las vistas de autenticación permanezcan en el directorio resources/views/auth/
5. EL Directorio_Vistas DEBERÁ preservar las vistas de gestión de perfil en el directorio resources/views/profile/
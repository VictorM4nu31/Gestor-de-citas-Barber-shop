# Requirements Document

## Introduction

El sistema actual tiene un problema crítico donde los barberos creados por el administrador no pueden iniciar sesión. Esto se debe a que el proceso de creación de barberos no está creando correctamente los usuarios en la tabla `users` ni asignando los roles apropiados. Este documento define los requisitos para corregir el sistema de autenticación de barberos.

## Glossary

- **Sistema**: La aplicación web de gestión de citas de barbería
- **Admin**: Usuario con rol de administrador que puede crear barberos
- **Barbero**: Usuario con rol de barbero que puede gestionar sus citas
- **Usuario_Laravel**: Registro en la tabla `users` utilizado por Laravel para autenticación
- **Registro_Barbero**: Registro en la tabla `barberos` con información específica del barbero
- **Barbero_Activo**: Barbero que puede iniciar sesión y acceder al sistema
- **Barbero_Inactivo**: Barbero dado de baja que no puede acceder pero mantiene historial

## Requirements

### Requirement 1

**User Story:** Como administrador, quiero crear barberos que puedan iniciar sesión inmediatamente, para que puedan acceder a su panel de control sin problemas técnicos.

#### Acceptance Criteria

1. WHEN el Admin crea un barbero, THE Sistema SHALL crear automáticamente un Usuario_Laravel correspondiente
2. WHEN el Admin crea un barbero, THE Sistema SHALL asignar el rol "barbero" al Usuario_Laravel creado
3. WHEN el Admin crea un barbero, THE Sistema SHALL vincular el Registro_Barbero con el Usuario_Laravel mediante user_id
4. WHEN el Admin crea un barbero, THE Sistema SHALL hashear la contraseña y almacenarla en la tabla users
5. THE Sistema SHALL validar que el email del barbero sea único tanto en users como en barberos

### Requirement 2

**User Story:** Como barbero creado por el admin, quiero poder iniciar sesión con mi email y contraseña, para acceder a mi panel de control y gestionar mis citas.

#### Acceptance Criteria

1. WHEN un barbero intenta iniciar sesión, THE Sistema SHALL autenticar usando la tabla users
2. WHEN un barbero se autentica exitosamente, THE Sistema SHALL redirigir al dashboard de barbero
3. IF un barbero no tiene rol asignado, THEN THE Sistema SHALL denegar el acceso
4. THE Sistema SHALL mostrar mensajes de error claros si las credenciales son incorrectas

### Requirement 3

**User Story:** Como administrador, quiero actualizar la información de barberos existentes, para mantener los datos actualizados sin romper la autenticación.

#### Acceptance Criteria

1. WHEN el Admin actualiza un barbero, THE Sistema SHALL sincronizar los cambios con el Usuario_Laravel correspondiente
2. WHEN el Admin cambia el email de un barbero, THE Sistema SHALL actualizar tanto barberos como users
3. WHEN el Admin cambia la contraseña de un barbero, THE Sistema SHALL actualizar la contraseña hasheada en users
4. IF no existe Usuario_Laravel para un barbero, THEN THE Sistema SHALL crear uno automáticamente

### Requirement 4

**User Story:** Como administrador, quiero dar de baja a barberos temporalmente, para que no puedan acceder al sistema pero mantengan su historial de citas.

#### Acceptance Criteria

1. WHEN el Admin da de baja un barbero, THE Sistema SHALL marcar el barbero como inactivo
2. WHEN el Admin da de baja un barbero, THE Sistema SHALL desactivar el Usuario_Laravel correspondiente
3. WHEN un Barbero_Inactivo intenta iniciar sesión, THE Sistema SHALL denegar el acceso
4. THE Sistema SHALL mantener todo el historial de citas del barbero inactivo
5. WHEN el Admin reactiva un barbero, THE Sistema SHALL permitir nuevamente el acceso

### Requirement 5

**User Story:** Como administrador, quiero eliminar permanentemente barberos del sistema, para casos donde sea necesario borrar completamente sus datos.

#### Acceptance Criteria

1. WHEN el Admin elimina permanentemente un barbero, THE Sistema SHALL eliminar el Usuario_Laravel correspondiente
2. WHEN el Admin elimina permanentemente un barbero, THE Sistema SHALL eliminar el Registro_Barbero
3. THE Sistema SHALL mantener la integridad referencial con las citas existentes
4. THE Sistema SHALL confirmar la eliminación permanente antes de proceder
5. THE Sistema SHALL mostrar advertencias sobre la pérdida de datos

### Requirement 6

**User Story:** Como desarrollador, quiero que las migraciones estén organizadas correctamente, para mantener un esquema de base de datos limpio y consistente.

#### Acceptance Criteria

1. THE Sistema SHALL eliminar campos redundantes de password en la tabla barberos
2. THE Sistema SHALL asegurar que user_id sea obligatorio en la tabla barberos
3. THE Sistema SHALL agregar campos de estado (activo/inactivo) a la tabla barberos
4. THE Sistema SHALL mantener la relación correcta entre users y barberos
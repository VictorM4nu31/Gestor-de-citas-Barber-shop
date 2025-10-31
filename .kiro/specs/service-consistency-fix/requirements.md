# Requisitos - Consistencia de Servicios

## Introducción

Este documento define los requisitos para asegurar que los servicios mostrados en la vista principal (welcome) y en la vista de agendar citas sean consistentes, mostrando únicamente los servicios que el administrador ha marcado como publicados.

## Glosario

- **Sistema_Citas**: El sistema de gestión de citas de la barbería
- **Servicio_Publicado**: Un servicio que tiene el campo 'publicado' establecido como verdadero
- **Servicio_Barbero**: Un servicio específico que un barbero puede ofrecer, asignado por el administrador
- **Vista_Principal**: La página de bienvenida (welcome) donde se muestran los servicios disponibles
- **Vista_Agendar**: La página donde los usuarios seleccionan servicios para agendar citas
- **Usuario_Cliente**: Usuario con rol 'usuario' que puede agendar citas
- **Administrador**: Usuario con rol 'admin' que gestiona servicios y barberos
- **Barbero**: Usuario que ofrece servicios específicos asignados por el administrador

## Requisitos

### Requisito 1

**Historia de Usuario:** Como usuario cliente, quiero ver los mismos servicios disponibles tanto en la página principal como en la página de agendar citas, para tener una experiencia consistente.

#### Criterios de Aceptación

1. CUANDO un Usuario_Cliente accede a la Vista_Principal, EL Sistema_Citas DEBERÁ mostrar únicamente los Servicio_Publicado ordenados por el campo 'orden' y fecha de creación
2. CUANDO un Usuario_Cliente accede a la Vista_Agendar, EL Sistema_Citas DEBERÁ mostrar únicamente los Servicio_Publicado ordenados por el campo 'orden' y fecha de creación
3. EL Sistema_Citas DEBERÁ aplicar el mismo filtro de publicación en ambas vistas para garantizar consistencia
4. CUANDO un Administrador marca un servicio como no publicado, EL Sistema_Citas DEBERÁ ocultar ese servicio tanto en la Vista_Principal como en la Vista_Agendar
5. EL Sistema_Citas DEBERÁ mantener el mismo orden de servicios en ambas vistas utilizando el scope 'publicadosOrdenados'

### Requisito 2

**Historia de Usuario:** Como administrador, quiero asignar servicios específicos a cada barbero al momento de crearlos o editarlos, para que los clientes solo vean los servicios que cada barbero realmente ofrece.

#### Criterios de Aceptación

1. CUANDO un Administrador crea un nuevo Barbero, EL Sistema_Citas DEBERÁ permitir seleccionar múltiples Servicio_Publicado para asignar al Barbero
2. CUANDO un Administrador edita un Barbero existente, EL Sistema_Citas DEBERÁ permitir modificar los Servicio_Barbero asignados
3. EL Sistema_Citas DEBERÁ almacenar la relación entre Barbero y Servicio_Barbero en la base de datos
4. CUANDO un Usuario_Cliente selecciona un Barbero en la Vista_Agendar, EL Sistema_Citas DEBERÁ mostrar únicamente los Servicio_Barbero asignados a ese Barbero
5. EL Sistema_Citas DEBERÁ validar que los servicios seleccionados en el formulario de citas estén asignados al barbero seleccionado

### Requisito 3

**Historia de Usuario:** Como administrador, quiero que los servicios no publicados no aparezcan en ninguna vista pública, para tener control total sobre qué servicios están disponibles para los clientes.

#### Criterios de Aceptación

1. CUANDO un Administrador establece un servicio como no publicado, EL Sistema_Citas DEBERÁ excluir ese servicio de todas las vistas públicas
2. EL Sistema_Citas DEBERÁ utilizar el scope 'publicadosOrdenados' consistentemente en todas las vistas públicas
3. CUANDO un Usuario_Cliente intenta agendar una cita, EL Sistema_Citas DEBERÁ permitir seleccionar únicamente Servicio_Publicado que también sean Servicio_Barbero
4. EL Sistema_Citas DEBERÁ validar que los servicios seleccionados estén tanto publicados como asignados al barbero antes de procesar la cita
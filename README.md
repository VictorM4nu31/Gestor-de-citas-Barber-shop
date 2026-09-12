# Gestor de Citas — Barber Shop

Aplicación web en Laravel para gestionar una barbería: catálogo público de servicios y barberos, reserva de citas con control de disponibilidad, agenda por barbero, galería de trabajos y panel de administración con roles y permisos.

Pensada para tres perfiles: **cliente** (reserva y gestiona sus citas), **barbero** (ve su agenda y marca citas como atendidas) y **admin** (gestiona barberos, servicios, citas y galería).

## Funcionalidades

- Catálogo público de servicios (precio, duración, publicados y ordenados) y barberos (especialidad, experiencia).
- Reserva de citas con comprobación de disponibilidad, huecos libres por barbero/servicio y repetición de citas.
- Confirmación y cancelación de citas según rol.
- Panel admin (`/admin`): CRUD de barberos (con baja/reactivación y borrado permanente), servicios, citas y galería con seguridad y rate limiting en subidas.
- Panel barbero (`/barbero`): agenda propia y marcado de citas atendidas.
- Roles y permisos con `spatie/laravel-permission`: `admin`, `barbero`, `usuario`.
- Autenticación con `laravel/breeze`; frontend con Tailwind CSS + Alpine.js + Vite.
- Métricas de traducciones faltantes y caché de traducciones (solo admin).

## Stack

- PHP `^8.2`, Laravel `^13.0`
- `laravel/breeze` `^2.3`, `spatie/laravel-permission` `^7.0`
- Tailwind CSS `^3.1`, Alpine.js, Vite `^6.3`, Flowbite
- Base de datos: MySQL por defecto (vale SQLite/PostgreSQL)
- Tests: Pest `^3.8`; estilo con Laravel Pint

## Requisitos

- PHP >= 8.2 con extensiones `gd` o `imagick` (procesado de imágenes de la galería), `mbstring`, `sqlite`/`mysql` según driver
- Composer
- Node.js con NPM
- MySQL en local, o SQLite para desarrollo rápido

## Instalación

```bash
git clone https://github.com/VictorM4nu31/Gestor-de-citas-Barber-shop.git
cd Gestor-de-citas-Barber-shop

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Crea la base de datos antes de migrar (ej. `gestor_citas_barber_shop` en MySQL y ajusta el `.env`), luego:

```bash
php artisan migrate --seed
php artisan storage:link
```

Arranca en desarrollo (dos terminales o `composer run dev` si está definido):

```bash
npm run dev
php artisan serve
```

Para producción compila antes:

```bash
npm run build
php artisan serve
```

Abre `http://127.0.0.1:8000`.

Para resetear todo en desarrollo:

```bash
php artisan migrate:fresh --seed
```

## Acceso demo (seeders)

El `migrate --seed` ejecuta `PermissionSeeder`, `AdminUserSeeder`, `ServicioSeeder` y `BarberoSeeder`:

| Rol | Email | Password | Notas |
|---|---|---|---|
| Admin | `admin@barbershop.com` | `admin123` | Acceso a `/admin/dashboard` |
| Barbero | `juan.perez@example.com` y otros 4 del `BarberoSeeder` | `password123` | Acceso a `/barbero/dashboard` |
| Cliente | Registro desde `/register` | — | Rol `usuario`, acceso a `/dashboard` y `/citas` |

Cambia estas claves en producción.

## Rutas principales

- Público: `/`, `/barberos`, `/barberos/{barbero}`, `/servicios`, `/servicios/{servicio}`
- Cliente (auth): `/dashboard`, `/citas` (reservar, repetir `/citas/{cita}/repeat`, confirmar, comprobar disponibilidad)
- Admin (`auth` + `role:admin`, prefijo `/admin`): dashboard, barberos, servicios, citas y galería (`/admin/gallery`, con middleware `gallery.security` y `gallery.rate_limit` en subidas)
- Barbero (`auth` + `role:barbero`, prefijo `/barbero`): dashboard, agenda y detalle de cita, marcar atendida
- Métricas de traducción (admin): `/admin/translation-metrics`, `/admin/translation-metrics/missing`

## Configuración (.env)

| Variable | Para qué | Default en `.env.example` |
|---|---|---|
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Conexión a BD | `mysql`, `127.0.0.1:3306`, `gestor_citas_barber_shop`, `root`, vacío |
| `GALLERY_MAX_FILE_SIZE`, `GALLERY_MAX_FILES_PER_UPLOAD` | Límite de subida (5 MB, 10 archivos) | `5242880`, `10` |
| `GALLERY_MIN/MAX_WIDTH/HEIGHT` | Dimensiones aceptadas | `100`–`8000` px |
| `GALLERY_RATE_LIMIT_ATTEMPTS`, `GALLERY_RATE_LIMIT_DECAY` | Anti-abuso en subidas | `10`, `1` (min) |
| `GALLERY_PROCESS_MAX_WIDTH/HEIGHT`, `GALLERY_JPEG_QUALITY`, `GALLERY_WEBP_QUALITY`, thumbs | Procesado y thumbnails | `1920x1080`, `85/80`, `300x300` |
| `TRANSLATION_CACHE_ENABLED`, `TRANSLATION_CACHE_DURATION/STORE/PREFIX`, `TRANSLATION_PRELOAD_ENABLED`, `LOG_MISSING_TRANSLATIONS` | Caché y monitor de traducciones | `true`, `null/null/translations`, `false`, `true` |
| `MAIL_MAILER` | Correos en dev | `log` |
| `QUEUE_CONNECTION`, `CACHE_STORE`, `SESSION_DRIVER` | Colas/caché/sesión | `database` |

El resto de `GALLERY_*` (WebP, nitidez, caché de navegador, limpieza) tiene valores razonables por defecto; ver `.env.example`.

## Estructura y modelos

```text
app/Http/Controllers/   CitaController, BarberoController, ServicioController, AdminController, Admin/GalleryController
app/Models/             User, Barbero, Servicio, Cita, GalleryImage
routes/                 web.php, auth.php
database/seeders/       PermissionSeeder, AdminUserSeeder, ServicioSeeder, BarberoSeeder
docs/                   GALLERY_SECURITY.md, TRANSLATION_PERFORMANCE.md
```

- `User` ↔ `Barbero` (1:1 por `user_id`), `Barbero` ↔ `Servicio` (N:M), `Cita` referencia a barbero + servicio + usuario.
- Permisos explícitos en español en `PermissionSeeder` (ver/crear/editar/eliminar por entidad y alcance propio/asignado/todas).

## Comandos útiles

```bash
php artisan test --compact   # o vendor/bin/pest
vendor/bin/pint --dirty      # estilo antes de commitear
php artisan route:list --except-vendor
php artisan config:show app.name
```

## Problemas frecuentes

- `ViteException: Unable to locate file in Vite manifest`: ejecuta `npm run dev` en desarrollo o `npm run build` en producción.
- Imágenes de galería rotas: falta `php artisan storage:link` o permisos en `storage/`.
- Error de subida en galería: revisa `GALLERY_MAX_FILE_SIZE`, dimensiones y que PHP tenga `gd`/`imagick`; si te bloquea el rate limit espera 1 min o ajusta `GALLERY_RATE_LIMIT_*`.
- Tablas de permisos vacías: ejecuta `php artisan db:seed --class=PermissionSeeder` (o `migrate:fresh --seed`).
- No ves cambios de frontend: recompila (`npm run dev`/`build`).

## Documentación extra

- `docs/GALLERY_SECURITY.md`: rate limiting, validación MIME/tamaño/dimensiones, escaneo de contenido, cabeceras y permisos de la galería.
- `docs/TRANSLATION_PERFORMANCE.md`: caché de traducciones y monitor de faltantes.

## Licencia

MIT. Basado en [Laravel](https://laravel.com) ([MIT](https://opensource.org/licenses/MIT)).

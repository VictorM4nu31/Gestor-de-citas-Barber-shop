# Proyecto Laravel de Barbería

Este proyecto es una aplicación web desarrollada con Laravel para gestionar una barbería, incluyendo un catálogo de productos, programación de citas y una sección de administración.

## Requisitos

- PHP >= 7.4
- Composer
- Node.js con NPM o Yarn
- Base de datos MySQL (o cualquier otra base de datos soportada por Laravel)

## Instalación

Sigue estos pasos para configurar y ejecutar el proyecto en tu entorno local.

### 1. Clonar el repositorio

Clona el proyecto desde el repositorio:

```bash
git clone https://github.com/usuario/proyecto-laravel.git
cd proyecto-laravel

```

### 2. Instalar dependencias

## Instalar dependencias de PHP

Instala las dependencias de PHP usando Composer:

```bash
composer install
```

## Instalar dependencias de JavaScript

```bash
npm install
```

### 3. Configurar el entorno

Copia el archivo .env.example a .env:

```bash
cp .env.example .env
```

### 4. Generar la clave de la aplicación
Genera la clave de la aplicación Laravel:

```bash
php artisan key:generate
```

### 5. Ejecutar migraciones y seeders
Ejecuta las migraciones para crear las tablas en tu base de datos y opcionalmente ejecuta los seeders para poblar la base de datos con datos iniciales:

```bash
php artisan migrate
php artisan db:seed

```

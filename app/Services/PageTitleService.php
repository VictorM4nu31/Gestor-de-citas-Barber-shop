<?php

namespace App\Services;

class PageTitleService
{
    /**
     * Get the page title based on the current route
     */
    public static function getTitle(): string
    {
        $routeName = request()->route()?->getName();
        
        $titles = [
            'admin.dashboard' => 'Panel Administrativo - Barbería',
            'barbero.dashboard' => 'Panel Barbero - Barbería',
            'dashboard' => 'Dashboard - Barbería',
            'citas.create' => 'Agendar Cita - Barbería',
            'citas.index' => 'Mis Citas - Barbería',
            'citas.show' => 'Detalle de Cita - Barbería',
            'admin.barberos.index' => 'Gestión de Barberos - Barbería',
            'admin.barberos.create' => 'Crear Barbero - Barbería',
            'admin.barberos.edit' => 'Editar Barbero - Barbería',
            'admin.barberos.show' => 'Detalle Barbero - Barbería',
            'admin.servicios.index' => 'Gestión de Servicios - Barbería',
            'admin.servicios.create' => 'Crear Servicio - Barbería',
            'admin.servicios.edit' => 'Editar Servicio - Barbería',
            'barbero.citas.index' => 'Mis Citas - Barbería',
            'barbero.citas.show' => 'Detalle de Cita - Barbería',
            'profile.edit' => 'Editar Perfil - Barbería',
            'login' => 'Iniciar Sesión - Barbería',
            'register' => 'Registrarse - Barbería',
        ];

        return $titles[$routeName] ?? 'Barbería';
    }

    /**
     * Set a custom title for the current request
     */
    public static function setTitle(string $title): void
    {
        app()->instance('page.title', $title);
    }

    /**
     * Get custom title if set, otherwise get default title
     */
    public static function getCurrentTitle(): string
    {
        try {
            return app('page.title') ?? self::getTitle();
        } catch (\Exception $e) {
            return self::getTitle();
        }
    }
}
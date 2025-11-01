<?php

namespace App\Services;

use App\Models\Barbero;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class BarberoValidationService
{
    /**
     * Validar que un barbero puede ser dado de baja
     */
    public function validateCanDeactivate(Barbero $barbero): void
    {
        if (!$barbero->activo) {
            throw ValidationException::withMessages([
                'estado' => 'El barbero ya está inactivo.'
            ]);
        }

        // Verificar si tiene citas futuras
        $citasFuturas = $barbero->citas()
            ->where('fecha', '>=', now()->toDateString())
            ->count();

        if ($citasFuturas > 0) {
            throw ValidationException::withMessages([
                'citas' => "No se puede dar de baja al barbero porque tiene {$citasFuturas} cita(s) programada(s) para fechas futuras. Reprograme o cancele las citas primero."
            ]);
        }
    }

    /**
     * Validar que un barbero puede ser reactivado
     */
    public function validateCanReactivate(Barbero $barbero): void
    {
        if ($barbero->activo) {
            throw ValidationException::withMessages([
                'estado' => 'El barbero ya está activo.'
            ]);
        }

        // Verificar que el email no esté siendo usado por otro barbero activo
        $emailConflict = Barbero::where('email', $barbero->email)
            ->where('id', '!=', $barbero->id)
            ->where('activo', true)
            ->exists();

        if ($emailConflict) {
            throw ValidationException::withMessages([
                'email' => 'No se puede reactivar el barbero porque su email está siendo usado por otro barbero activo.'
            ]);
        }

        // Verificar que el email no esté siendo usado en la tabla users por otro usuario
        if ($barbero->user_id) {
            $userEmailConflict = User::where('email', $barbero->email)
                ->where('id', '!=', $barbero->user_id)
                ->exists();

            if ($userEmailConflict) {
                throw ValidationException::withMessages([
                    'email' => 'No se puede reactivar el barbero porque su email está siendo usado por otro usuario en el sistema.'
                ]);
            }
        }
    }

    /**
     * Validar que un barbero puede ser eliminado permanentemente
     */
    public function validateCanDelete(Barbero $barbero): void
    {
        // Verificar si tiene citas asociadas (historial)
        $citasCount = $barbero->citas()->count();

        if ($citasCount > 0) {
            throw ValidationException::withMessages([
                'citas' => "No se puede eliminar permanentemente el barbero porque tiene {$citasCount} cita(s) asociada(s) en el historial. Considere dar de baja en lugar de eliminar."
            ]);
        }

        // Verificar si está activo (debe estar inactivo para eliminación permanente)
        if ($barbero->activo) {
            throw ValidationException::withMessages([
                'estado' => 'No se puede eliminar permanentemente un barbero activo. Primero debe darlo de baja.'
            ]);
        }
    }

    /**
     * Validar que un barbero puede realizar operaciones (debe estar activo)
     */
    public function validateIsActive(Barbero $barbero): void
    {
        if (!$barbero->activo) {
            throw ValidationException::withMessages([
                'estado' => 'No se pueden realizar operaciones en un barbero inactivo.'
            ]);
        }
    }
}
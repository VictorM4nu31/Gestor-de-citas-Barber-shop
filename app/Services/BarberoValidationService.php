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
     * Validar unicidad de email en ambas tablas
     */
    public function validateEmailUniqueness(string $email, ?int $barberoId = null, ?int $userId = null): void
    {
        // Verificar unicidad en tabla barberos
        $barberoQuery = Barbero::where('email', $email);
        if ($barberoId) {
            $barberoQuery->where('id', '!=', $barberoId);
        }

        if ($barberoQuery->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Este correo electrónico ya está registrado por otro barbero.'
            ]);
        }

        // Verificar unicidad en tabla users
        $userQuery = User::where('email', $email);
        if ($userId) {
            $userQuery->where('id', '!=', $userId);
        }

        if ($userQuery->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Este correo electrónico ya está registrado por otro usuario en el sistema.'
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

    /**
     * Validar integridad de datos entre barbero y usuario
     */
    public function validateDataIntegrity(Barbero $barbero): void
    {
        // Verificar que el barbero tenga un usuario asociado
        if (!$barbero->user_id || !$barbero->user) {
            throw ValidationException::withMessages([
                'user' => 'El barbero debe tener un usuario asociado para funcionar correctamente.'
            ]);
        }

        // Verificar que los emails coincidan
        if ($barbero->email !== $barbero->user->email) {
            throw ValidationException::withMessages([
                'email' => 'El email del barbero debe coincidir con el email del usuario asociado.'
            ]);
        }

        // Verificar que el usuario tenga el rol de barbero
        if (!$barbero->user->hasRole('barbero')) {
            throw ValidationException::withMessages([
                'role' => 'El usuario asociado debe tener el rol de barbero.'
            ]);
        }
    }

    /**
     * Validar que se puede cambiar el estado de un barbero
     */
    public function validateStateChange(Barbero $barbero, bool $newState): void
    {
        if ($barbero->activo === $newState) {
            $estadoTexto = $newState ? 'activo' : 'inactivo';
            throw ValidationException::withMessages([
                'estado' => "El barbero ya está {$estadoTexto}."
            ]);
        }

        if ($newState) {
            // Validaciones para activar
            $this->validateCanReactivate($barbero);
        } else {
            // Validaciones para desactivar
            $this->validateCanDeactivate($barbero);
        }
    }
}
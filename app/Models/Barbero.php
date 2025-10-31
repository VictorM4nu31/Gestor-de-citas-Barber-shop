<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;

class Barbero extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'nombre_completo',
        'email',
        'telefono',
        'especialidad',
        'experiencia',
        'foto',
        'user_id',
        'activo',
        'fecha_baja',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_baja' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function ($barbero) {
            if ($barbero->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($barbero->foto);
            }
        });

        // Validar integridad de datos antes de guardar
        static::saving(function ($barbero) {
            // Validar que el email esté presente
            if (empty($barbero->email)) {
                throw new \InvalidArgumentException('El email del barbero es obligatorio.');
            }

            // Validar formato de email
            if (!filter_var($barbero->email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('El formato del email no es válido.');
            }

            // Validar que user_id esté presente para barberos activos
            if ($barbero->activo && !$barbero->user_id) {
                throw new \InvalidArgumentException('Un barbero activo debe tener un usuario asociado.');
            }
        });

        // Validar antes de actualizar
        static::updating(function ($barbero) {
            // Si se está activando un barbero, validar que tenga usuario
            if ($barbero->isDirty('activo') && $barbero->activo && !$barbero->user_id) {
                throw new \InvalidArgumentException('No se puede activar un barbero sin usuario asociado.');
            }

            // Si se está cambiando el email, validar unicidad
            if ($barbero->isDirty('email')) {
                $emailExists = static::where('email', $barbero->email)
                    ->where('id', '!=', $barbero->id)
                    ->exists();
                
                if ($emailExists) {
                    throw new \InvalidArgumentException('El email ya está siendo usado por otro barbero.');
                }
            }
        });
    }

    // Quitar el hidden del password y mutador setPasswordAttribute
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_barbero');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes para filtrar barberos activos/inactivos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    /**
     * Verificar si el barbero puede ser dado de baja
     */
    public function canBeDeactivated(): bool
    {
        if (!$this->activo) {
            return false;
        }

        // Verificar si tiene citas futuras
        $citasFuturas = $this->citas()
            ->where('fecha', '>=', now()->toDateString())
            ->count();

        return $citasFuturas === 0;
    }

    /**
     * Verificar si el barbero puede ser reactivado
     */
    public function canBeReactivated(): bool
    {
        if ($this->activo) {
            return false;
        }

        // Verificar que el email no esté siendo usado por otro barbero activo
        $emailConflict = static::where('email', $this->email)
            ->where('id', '!=', $this->id)
            ->where('activo', true)
            ->exists();

        return !$emailConflict;
    }

    /**
     * Verificar si el barbero puede ser eliminado permanentemente
     */
    public function canBeDeleted(): bool
    {
        // Debe estar inactivo
        if ($this->activo) {
            return false;
        }

        // No debe tener citas asociadas
        return $this->citas()->count() === 0;
    }

    /**
     * Verificar integridad de datos con usuario asociado
     */
    public function hasValidUserIntegrity(): bool
    {
        if (!$this->user_id || !$this->user) {
            return false;
        }

        // Verificar que los emails coincidan
        if ($this->email !== $this->user->email) {
            return false;
        }

        // Verificar que el usuario tenga el rol de barbero
        return $this->user->hasRole('barbero');
    }

    /**
     * Obtener el estado del barbero como texto
     */
    public function getEstadoTextoAttribute(): string
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }

    /**
     * Obtener el tiempo desde la fecha de baja
     */
    public function getTiempoBajaAttribute(): ?string
    {
        if (!$this->fecha_baja) {
            return null;
        }

        return $this->fecha_baja->diffForHumans();
    }
}

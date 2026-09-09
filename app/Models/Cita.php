<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo', 'numero_telefono', 'correo_electronico',
        'fecha', 'hora', 'servicios', 'id_barbero', 'id_usuario', 'costo',
        'estado', 'fecha_atencion',
    ];

    protected $casts = [
        'fecha_atencion' => 'datetime',
    ];

    public function barbero()
    {
        return $this->belongsTo(Barbero::class, 'id_barbero');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function serviciosMany()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio', 'cita_id', 'servicio_id');
    }

    /**
     * Return service names from the pivot relation, with a legacy fallback.
     *
     * @return array<int, string>
     */
    public function getServiciosNamesAttribute(): array
    {
        $servicios = $this->relationLoaded('serviciosMany')
            ? $this->serviciosMany
            : $this->serviciosMany()->get();

        if ($servicios->isNotEmpty()) {
            return $servicios->pluck('nombre')->all();
        }

        if (empty($this->servicios)) {
            return [];
        }

        $serviciosIds = explode(',', $this->servicios);

        return Servicio::whereIn('id', $serviciosIds)->pluck('nombre')->toArray();
    }

    public function getServiciosNombresTextoAttribute(): string
    {
        return implode(', ', $this->servicios_names);
    }

    public static function usuarioTieneMaximasFuturas($userId, $max = 2): bool
    {
        return static::where('id_usuario', $userId)
            ->where('fecha', '>=', now()->toDateString())
            ->count() >= $max;
    }

    public static function barberoNoDisponible($barberoId, $fecha, $hora): bool
    {
        return static::where('id_barbero', $barberoId)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->exists();
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAtendidas($query)
    {
        return $query->where('estado', 'atendida');
    }

    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }

    public function marcarComoAtendida()
    {
        $this->update([
            'estado' => 'atendida',
            'fecha_atencion' => now(),
        ]);
    }

    public function marcarComoCancelada()
    {
        $this->update([
            'estado' => 'cancelada',
        ]);
    }

    public function puedeSerAtendida(): bool
    {
        return $this->estado === 'pendiente' &&
               $this->fecha <= now()->toDateString();
    }

    public function getEstadoTextoAttribute()
    {
        return match ($this->estado) {
            'pendiente' => 'Pendiente',
            'atendida' => 'Atendida',
            'cancelada' => 'Cancelada',
            default => 'Desconocido'
        };
    }

    protected static function booted()
    {
        static::deleting(function ($cita) {
            $cita->serviciosMany()->detach();
        });
    }
}

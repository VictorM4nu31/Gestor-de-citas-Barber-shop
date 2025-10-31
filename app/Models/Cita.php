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

    public function getServiciosNamesAttribute()
    {
        if (empty($this->servicios)) {
            return [];
        }
        // Split by solo coma, sin espacios.
        $serviciosIds = explode(',', $this->servicios);
        return Servicio::whereIn('id', $serviciosIds)->pluck('nombre')->toArray();
    }

    // Opcional: para mostrar todos los nombres como texto
    public function getServiciosNombresTextoAttribute()
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

    // Scopes para filtrar por estado
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

    // Métodos para cambiar estado
    public function marcarComoAtendida()
    {
        $this->update([
            'estado' => 'atendida',
            'fecha_atencion' => now()
        ]);
    }

    public function marcarComoCancelada()
    {
        $this->update([
            'estado' => 'cancelada'
        ]);
    }

    // Verificar si puede ser marcada como atendida
    public function puedeSerAtendida()
    {
        return $this->estado === 'pendiente' && 
               $this->fecha <= now()->toDateString();
    }

    // Obtener el estado como texto
    public function getEstadoTextoAttribute()
    {
        return match($this->estado) {
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

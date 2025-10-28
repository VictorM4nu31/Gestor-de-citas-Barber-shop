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
    ];

    public function barbero()
    {
        return $this->belongsTo(Barbero::class, 'id_barbero');
    }

    public function getServiciosNamesAttribute()
    {
        $serviciosIds = explode(', ', $this->servicios);
        $serviciosNames = Servicio::whereIn('id', $serviciosIds)->pluck('nombre')->toArray();
        return implode(', ', $serviciosNames);
    }
}

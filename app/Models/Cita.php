<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'numero_telefono',
        'correo_electronico',
        'fecha',
        'hora',
        'id_servicio',
        'id_barbero',
    ];

    // Definir las relaciones con los modelos Servicio y Barbero
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function barbero()
    {
        return $this->belongsTo(Barbero::class, 'id_barbero');
    }
}

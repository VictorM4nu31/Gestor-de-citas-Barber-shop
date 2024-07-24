<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion',
        'precio',
        'foto',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio');
    }
    // Si necesitas agregar relaciones u otras funcionalidades, hazlo aquí
}


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

    // Relación con las citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio');
    }
}

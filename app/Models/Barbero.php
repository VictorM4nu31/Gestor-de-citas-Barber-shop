<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbero extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'email',
        'telefono',
        'especialidad',
        'experiencia',
        'foto',
    ];

    // Relación con las citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_barbero');
    }
}


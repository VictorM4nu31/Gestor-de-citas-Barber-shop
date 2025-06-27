<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Barbero extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'nombre_completo',
        'email',
        'password',
        'telefono',
        'especialidad',
        'experiencia',
        'foto',
    ];

    // Si necesitas agregar relaciones u otras funcionalidades, hazlo aquí
}

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
        'publicado',
        'orden',
    ];

    /**
     * Scope para servicios publicados y ordenados.
     */
    public function scopePublicadosOrdenados($query)
    {
        return $query->where('publicado', true)->orderBy('orden', 'asc')->orderBy('created_at', 'desc');
    }

    // Si necesitas agregar relaciones u otras funcionalidades, hazlo aquí
}


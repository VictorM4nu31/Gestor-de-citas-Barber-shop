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

    public function barberos()
    {
        return $this->belongsToMany(Barbero::class, 'barbero_servicio');
    }
}


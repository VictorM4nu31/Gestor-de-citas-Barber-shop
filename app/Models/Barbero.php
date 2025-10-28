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

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Encriptar la contraseña
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_barbero');
    }
    // Si necesitas agregar relaciones u otras funcionalidades, hazlo aquí
}

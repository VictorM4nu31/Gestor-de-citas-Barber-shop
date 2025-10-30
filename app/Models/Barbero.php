<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;

class Barbero extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'nombre_completo',
        'email',
        'telefono',
        'especialidad',
        'experiencia',
        'foto',
        'user_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($barbero) {
            if ($barbero->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($barbero->foto);
            }
        });
    }

    // Quitar el hidden del password y mutador setPasswordAttribute
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_barbero');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Si necesitas agregar relaciones u otras funcionalidades, hazlo aquí
}

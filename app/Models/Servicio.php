<?php

namespace App\Models;

use App\Helpers\TranslationHelper;
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
        'nombre_en',
        'descripcion_en',
    ];

    /**
     * Scope para servicios publicados y ordenados.
     */
    public function scopePublicadosOrdenados($query)
    {
        return $query->where('publicado', true)
            ->orderBy('servicios.orden', 'asc')
            ->orderBy('servicios.created_at', 'desc');
    }

    public function barberos()
    {
        return $this->belongsToMany(Barbero::class, 'barbero_servicio');
    }

    /**
     * Get translated name for the service
     */
    public function getTranslatedName(?string $locale = null): string
    {
        return TranslationHelper::getTranslatedAttribute($this, 'nombre', $locale);
    }

    /**
     * Get translated description for the service
     */
    public function getTranslatedDescription(?string $locale = null): string
    {
        return TranslationHelper::getTranslatedAttribute($this, 'descripcion', $locale);
    }

    /**
     * Get all translated attributes for the service
     */
    public function getTranslatedAttributes(?string $locale = null): array
    {
        return [
            'nombre' => $this->getTranslatedName($locale),
            'descripcion' => $this->getTranslatedDescription($locale),
            'duracion' => $this->duracion,
            'precio' => $this->precio,
            'foto' => $this->foto,
            'publicado' => $this->publicado,
            'orden' => $this->orden,
        ];
    }
}

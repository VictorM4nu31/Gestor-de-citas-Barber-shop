<?php

namespace App\Http\Requests\Cita;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_completo' => 'required|string|max:255',
            'numero_telefono' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|string',
            'servicios' => 'required|array|min:1',
            'servicios.*' => [
                'integer',
                'exists:servicios,id',
                function ($attribute, $value, $fail) {
                    // Verificar que el servicio esté publicado
                    $servicio = \App\Models\Servicio::find($value);
                    if (!$servicio || !$servicio->publicado) {
                        $fail('El servicio seleccionado no está disponible.');
                        return;
                    }

                    // Verificar que el servicio esté asignado al barbero
                    $barberoId = $this->input('id_barbero');
                    if ($barberoId) {
                        $barbero = \App\Models\Barbero::find($barberoId);
                        if ($barbero && !$barbero->servicios()->where('servicios.id', $value)->exists()) {
                            $fail('El servicio seleccionado no está disponible para este barbero.');
                        }
                    }
                }
            ],
            'id_barbero' => 'required|integer|exists:barberos,id',
        ];
    }
}

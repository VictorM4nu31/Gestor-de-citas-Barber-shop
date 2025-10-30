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
            'servicios.*' => 'integer|exists:servicios,id',
            'id_barbero' => 'required|integer|exists:barberos,id',
        ];
    }
}

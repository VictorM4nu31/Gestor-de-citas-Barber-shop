<?php

namespace App\Http\Requests\Barbero;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:barberos,email',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:100',
            'experiencia' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'foto.max' => 'El tamaño máximo permitido de la imagen es de 2MB.',
        ];
    }
}

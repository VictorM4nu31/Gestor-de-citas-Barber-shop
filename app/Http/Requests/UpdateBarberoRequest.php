<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use App\Rules\UniqueEmailAcrossTables;

class UpdateBarberoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $barbero = $this->route('barbero');
        $userId = $barbero->user_id ?? null;

        return [
            'nombre_completo' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', // Solo letras y espacios
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                new UniqueEmailAcrossTables($barbero->id, $userId),
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'telefono' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/', // Formato de teléfono válido
            ],
            'especialidad' => [
                'required',
                'string',
                'max:255',
            ],
            'experiencia' => [
                'required',
                'string',
                'max:1000',
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048', // 2MB máximo
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.regex' => 'El nombre completo solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'telefono.regex' => 'El formato del teléfono no es válido.',
            'especialidad.required' => 'La especialidad es obligatoria.',
            'experiencia.required' => 'La experiencia es obligatoria.',
            'experiencia.max' => 'La experiencia no puede exceder 1000 caracteres.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg o webp.',
            'foto.max' => 'La imagen no puede ser mayor a 2MB.',
            'foto.dimensions' => 'La imagen debe tener entre 100x100 y 2000x2000 píxeles.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre_completo' => 'nombre completo',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'telefono' => 'teléfono',
            'especialidad' => 'especialidad',
            'experiencia' => 'experiencia',
            'foto' => 'foto',
        ];
    }
}
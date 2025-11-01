<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Rules\UniqueEmailAcrossTables;

class StoreBarberoRequest extends FormRequest
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
                new UniqueEmailAcrossTables(),
            ],
            'password' => [
                'required',
                'confirmed',
                'min:6', // Reducido de 8 a 6 caracteres
                'max:255',
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
            'nombre_completo.required' => __('validation.custom.nombre_completo.required'),
            'nombre_completo.regex' => __('validation.custom.nombre_completo.regex'),
            'email.required' => __('validation.custom.email.required'),
            'email.email' => __('validation.custom.email.email'),
            'email.unique' => __('validation.custom.email.unique'),
            'password.required' => __('validation.custom.password.required'),
            'password.confirmed' => __('validation.custom.password.confirmed'),
            'password.min' => __('validation.custom.password.min'),
            'telefono.regex' => __('validation.custom.telefono.regex'),
            'especialidad.required' => __('validation.custom.especialidad.required'),
            'experiencia.required' => __('validation.custom.experiencia.required'),
            'experiencia.max' => __('validation.custom.experiencia.max'),
            'foto.image' => __('validation.custom.foto.image'),
            'foto.mimes' => __('validation.custom.foto.mimes'),
            'foto.max' => __('validation.custom.foto.max'),
            'foto.dimensions' => __('validation.custom.foto.dimensions'),
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
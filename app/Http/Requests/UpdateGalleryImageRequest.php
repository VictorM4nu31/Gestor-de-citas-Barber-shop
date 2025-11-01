<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryImageRequest extends FormRequest
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
            'alt_text' => [
                'nullable',
                'string',
                'max:255',
            ],
            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'alt_text.string' => 'El texto alternativo debe ser una cadena de texto.',
            'alt_text.max' => 'El texto alternativo no puede exceder 255 caracteres.',
            'display_order.integer' => 'El orden de visualización debe ser un número entero.',
            'display_order.min' => 'El orden de visualización debe ser mayor o igual a 0.',
            'is_active.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'alt_text' => 'texto alternativo',
            'display_order' => 'orden de visualización',
            'is_active' => 'estado activo',
        ];
    }
}
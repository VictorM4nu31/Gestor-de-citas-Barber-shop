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
            'alt_text.string' => __('validation.string'),
            'alt_text.max' => __('validation.max.string'),
            'display_order.integer' => __('validation.integer'),
            'display_order.min' => __('validation.min.numeric'),
            'is_active.boolean' => __('validation.boolean'),
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
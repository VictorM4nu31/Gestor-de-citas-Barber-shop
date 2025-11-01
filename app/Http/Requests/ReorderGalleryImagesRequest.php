<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderGalleryImagesRequest extends FormRequest
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
            'images' => [
                'required',
                'array',
                'min:1',
            ],
            'images.*.id' => [
                'required',
                'integer',
                'exists:gallery_images,id',
            ],
            'images.*.display_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'images.required' => 'Debe proporcionar al menos una imagen para reordenar.',
            'images.array' => 'El formato de las imágenes no es válido.',
            'images.min' => 'Debe proporcionar al menos una imagen para reordenar.',
            'images.*.id.required' => 'Cada imagen debe tener un ID válido.',
            'images.*.id.integer' => 'El ID de cada imagen debe ser un número entero.',
            'images.*.id.exists' => 'Una o más imágenes no existen en la galería.',
            'images.*.display_order.required' => 'Cada imagen debe tener un orden de visualización.',
            'images.*.display_order.integer' => 'El orden de visualización debe ser un número entero.',
            'images.*.display_order.min' => 'El orden de visualización debe ser mayor o igual a 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'images' => 'imágenes',
            'images.*.id' => 'ID de imagen',
            'images.*.display_order' => 'orden de visualización',
        ];
    }
}
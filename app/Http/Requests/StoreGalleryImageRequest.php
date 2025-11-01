<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryImageRequest extends FormRequest
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
                'max:10',
            ],
            'images.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120', // 5MB máximo
                'dimensions:min_width=100,min_height=100,max_width=8000,max_height=8000',
                function ($attribute, $value, $fail) {
                    // Additional security validation
                    if (!$value->isValid()) {
                        $fail('El archivo no es válido.');
                        return;
                    }

                    // Check for suspicious file content
                    $content = file_get_contents($value->getPathname());
                    if (strpos($content, '<?php') !== false || strpos($content, '<?=') !== false) {
                        $fail('El archivo contiene contenido no permitido.');
                        return;
                    }

                    // Verify actual image content
                    $imageInfo = getimagesize($value->getPathname());
                    if (!$imageInfo) {
                        $fail('El archivo no es una imagen válida.');
                        return;
                    }

                    // Check MIME type consistency
                    if ($imageInfo['mime'] !== $value->getMimeType()) {
                        $fail('El tipo de archivo no coincide con su contenido.');
                        return;
                    }
                },
            ],
            'alt_texts' => [
                'nullable',
                'array',
            ],
            'alt_texts.*' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_.,!?áéíóúñÁÉÍÓÚÑ]*$/', // Only allow safe characters
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'images.required' => 'Debe seleccionar al menos una imagen.',
            'images.array' => 'El formato de las imágenes no es válido.',
            'images.min' => 'Debe seleccionar al menos una imagen.',
            'images.max' => 'No puede subir más de 10 imágenes a la vez.',
            'images.*.required' => 'Cada archivo debe ser una imagen válida.',
            'images.*.image' => 'Cada archivo debe ser una imagen.',
            'images.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png o webp.',
            'images.*.max' => 'Cada imagen no puede ser mayor a 5MB.',
            'images.*.dimensions' => 'Cada imagen debe tener entre 100x100 y 4000x4000 píxeles.',
            'alt_texts.array' => 'El formato de los textos alternativos no es válido.',
            'alt_texts.*.string' => 'Cada texto alternativo debe ser una cadena de texto.',
            'alt_texts.*.max' => 'Cada texto alternativo no puede exceder 255 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'images' => 'imágenes',
            'images.*' => 'imagen',
            'alt_texts' => 'textos alternativos',
            'alt_texts.*' => 'texto alternativo',
        ];
    }
}
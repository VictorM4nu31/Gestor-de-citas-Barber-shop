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
            'images.required' => __('messages.gallery.no_images_selected'),
            'images.array' => __('validation.custom.images.array'),
            'images.min' => __('messages.gallery.no_images_selected'),
            'images.*.id.required' => __('validation.required'),
            'images.*.id.integer' => __('validation.integer'),
            'images.*.id.exists' => __('validation.exists'),
            'images.*.display_order.required' => __('validation.required'),
            'images.*.display_order.integer' => __('validation.integer'),
            'images.*.display_order.min' => __('validation.min.numeric'),
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
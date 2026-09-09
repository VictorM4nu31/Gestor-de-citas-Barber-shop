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
                'dimensions:min_width=50,min_height=50,max_width=10000,max_height=10000',
                function ($attribute, $value, $fail) {
                    // Additional security validation
                    if (! $value->isValid()) {
                        $fail(__('validation.custom_rules.invalid_file'));

                        return;
                    }

                    // Verify actual image content
                    $imageInfo = getimagesize($value->getPathname());
                    if (! $imageInfo) {
                        $fail(__('validation.custom_rules.invalid_image_content'));

                        return;
                    }

                    // Check MIME type consistency (relaxed - allow common variations)
                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (! in_array($imageInfo['mime'], $allowedMimes)) {
                        $fail(__('validation.custom_rules.image_type_not_allowed'));

                        return;
                    }

                    // Basic security check - only scan first 1KB for obvious executable content
                    $handle = fopen($value->getPathname(), 'rb');
                    if ($handle) {
                        $firstKB = fread($handle, 1024);
                        fclose($handle);

                        // Only check for obvious executable patterns at the beginning of file
                        if (preg_match('/^<\?php|^<\?=|^#!/', $firstKB)) {
                            $fail(__('validation.custom_rules.executable_content_detected'));

                            return;
                        }
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
            'images.required' => __('validation.custom.images.required'),
            'images.array' => __('validation.custom.images.array'),
            'images.min' => __('validation.custom.images.min'),
            'images.max' => __('validation.custom.images.max'),
            'images.*.required' => __('validation.custom.images.*.required'),
            'images.*.image' => __('validation.custom.images.*.image'),
            'images.*.mimes' => __('validation.custom.images.*.mimes'),
            'images.*.max' => __('validation.custom.images.*.max'),
            'images.*.dimensions' => __('validation.custom.images.*.dimensions'),
            'alt_texts.array' => __('validation.custom.alt_texts.array'),
            'alt_texts.*.string' => __('validation.custom.alt_texts.*.string'),
            'alt_texts.*.max' => __('validation.custom.alt_texts.*.max'),
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

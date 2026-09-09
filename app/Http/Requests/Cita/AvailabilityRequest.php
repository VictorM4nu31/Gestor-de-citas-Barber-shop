<?php

namespace App\Http\Requests\Cita;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AvailabilityRequest extends FormRequest
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
            'barbero_id' => ['required', 'integer', 'exists:barberos,id'],
            'fecha' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => ['required', 'integer', 'exists:servicios,id'],
        ];
    }
}

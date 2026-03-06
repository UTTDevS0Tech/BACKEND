<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EspecialidadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ];
    }

    public function messages() {
        return [
            'nombre.required' => 'El nombre de la especialidad es obligatorio.',
            'nombre.string' => 'El nombre de la especialidad debe ser una cadena de texto.',
            'nombre.max' => 'El nombre de la especialidad no puede exceder los 255 caracteres.',
            'descripcion.string' => 'La descripción de la especialidad debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción de la especialidad no puede exceder los 1000 caracteres.',
        ];
    }

    
}

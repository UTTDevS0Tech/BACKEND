<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReagendarCitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'personal_id' => 'nullable|exists:personales,id',
            'hora_c' => 'required|date_format:H:i',
            'fecha_c' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'personal_id.exists' => 'El estilista seleccionado no existe.',
            'hora_c.required' => 'La nueva hora es obligatoria.',
            'hora_c.date_format' => 'La hora debe tener formato HH:MM.',
            'fecha_c.required' => 'La nueva fecha es obligatoria.',
            'fecha_c.date' => 'La fecha no es válida.',
        ];
    }
}

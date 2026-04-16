<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisponibilidadRequest extends FormRequest
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
            'personal_id' => 'required|exists:personales,id',
            'fecha' => 'required|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'personal_id.required' => 'El estilista es obligatorio.',
            'personal_id.exists' => 'El estilista seleccionado no existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'fecha.after_or_equal' => 'No puedes consultar disponibilidad de fechas anteriores a hoy.',
        ];
    }
}

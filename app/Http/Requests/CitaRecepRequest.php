<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaRecepRequest extends FormRequest
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
            'apartado' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'personal_id' => 'required|exists:personales,id',
            'hora_c' => 'required|date_format:H:i',
            'fecha_c' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
        ];
    }

    public function messages()
    {
        return [
            'apartado.numeric' => 'El apartado debe ser numérico.',
            'apartado.min' => 'El apartado no puede ser menor a 0.',
            'total.numeric' => 'El total debe ser numérico.',
            'total.min' => 'El total no puede ser menor a 0.',
            'personal_id.required' => 'El estilista es obligatorio.',
            'personal_id.exists' => 'El estilista seleccionado no existe.',
            'hora_c.required' => 'La hora de la cita es obligatoria.',
            'hora_c.date_format' => 'La hora debe tener formato HH:MM.',
            'fecha_c.required' => 'La fecha de la cita es obligatoria.',
            'fecha_c.date' => 'La fecha no es válida.',
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ];
    }
}

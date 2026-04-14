<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorarioSemanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'horarios' => ['required', 'array', 'min:1'],
            'horarios.*.dia_semana' => [
                'required',
                'string',
                'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'
            ],
            'horarios.*.hora_inicio' => ['required', 'date_format:H:i'],
            'horarios.*.hora_fin' => ['required', 'date_format:H:i', 'after:horarios.*.hora_inicio'],
            'horarios.*.activo' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'horarios.required' => 'Debes enviar los horarios.',
            'horarios.array' => 'Los horarios deben enviarse como arreglo.',
            'horarios.min' => 'Debes agregar al menos un día.',
            'horarios.*.dia_semana.required' => 'El día de la semana es obligatorio.',
            'horarios.*.dia_semana.in' => 'Uno de los días enviados no es válido.',
            'horarios.*.hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'horarios.*.hora_inicio.date_format' => 'La hora de inicio debe tener formato HH:MM.',
            'horarios.*.hora_fin.required' => 'La hora de fin es obligatoria.',
            'horarios.*.hora_fin.date_format' => 'La hora de fin debe tener formato HH:MM.',
            'horarios.*.hora_fin.after' => 'La hora de fin debe ser mayor que la hora de inicio.',
            'horarios.*.activo.required' => 'Debes indicar si el día está activo.',
            'horarios.*.activo.boolean' => 'El campo activo debe ser verdadero o falso.',
        ];
    }
}


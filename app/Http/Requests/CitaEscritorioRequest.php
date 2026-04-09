<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaEscritorioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'total' => 'required|numeric|min:0',
            'personal_id' => 'required|exists:personales,id',
            'hora_c' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:20:00',
            'fecha_c' => 'required|date|after_or_equal:today|before_or_equal:2050-12-31',
            'estado' => 'sometimes|in:pendiente,confirmada,cancelada,completada',
            'apartado' => 'nullable|numeric|min:0',
            'cliente_id' => 'required|exists:clientes,id',

            'detalles' => 'required|array|min:1',
            'detalles.*.servicio_id' => 'required|exists:tipo_servicios,id',
            'detalles.*.subtotal' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'hora_c.required' => 'La hora es obligatoria.',
            'hora_c.date_format' => 'La hora debe tener formato HH:MM.',
            'hora_c.after_or_equal' => 'La hora de la cita no puede ser antes de las 09:00.',
            'hora_c.before_or_equal' => 'La hora de la cita no puede ser después de las 20:00.',

            'fecha_c.required' => 'La fecha es obligatoria.',
            'fecha_c.date' => 'La fecha no es válida.',
            'fecha_c.after_or_equal' => 'La fecha de la cita no puede ser anterior a hoy.',
            'fecha_c.before_or_equal' => 'La fecha de la cita no puede ser mayor al 31-12-2050.',

            'detalles.required' => 'Debes enviar al menos un detalle.',
            'detalles.array' => 'Los detalles deben enviarse en formato de arreglo.',
            'detalles.min' => 'Debes agregar al menos un servicio.',
            'detalles.*.servicio_id.required' => 'Cada detalle debe tener un servicio.',
            'detalles.*.servicio_id.exists' => 'Uno de los servicios seleccionados no existe.',
            'detalles.*.subtotal.required' => 'Cada detalle debe tener subtotal.',
            'detalles.*.subtotal.numeric' => 'El subtotal debe ser numérico.',
            'detalles.*.subtotal.min' => 'El subtotal no puede ser negativo.',
        ];
    }
}
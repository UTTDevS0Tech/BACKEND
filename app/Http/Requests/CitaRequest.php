<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'apartado' => 'nullable|numeric|min:0',
            'personal_id' => 'required|exists:personales,id',
            'hora_c' => 'required',
            'fecha_c' => 'required|date|after_or_equal:today',
            'estado' => 'nullable|in:pendiente,confirmada,cancelada',
            'cliente_id' => 'nullable|exists:clientes,id',
            'hora_fin' => 'nullable|date_format:H:i',
            'detalle_cita' => 'required|array|min:1',
            'detalle_cita.*.tipo_servicio_id' => 'required|exists:tipo_servicios,id',
            'detalle_cita.*.precio_capturado' => 'required|numeric|min:0',
            'total'=> 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'apartado.nullable' => 'El apartado es opcional.',
            'personal_id.required' => 'El personal es obligatorio.',
            'hora_c.required' => 'La hora de la cita es obligatoria.',
            'fecha_c.required' => 'La fecha de la cita es obligatoria.',
            'fecha_c.after_or_equal' => 'La fecha de la cita no puede ser anterior a hoy.',
            'estado.required' => 'El estado de la cita es obligatorio.',
            'cliente_id.required' => 'El cliente es obligatorio.',
            'personal_id.exists' => 'El personal seleccionado no existe.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',

            'detalle_cita.required' => 'Debes agregar al menos un servicio a la cita.',
            'detalle_cita.array' => 'El detalle de la cita debe ser un arreglo.',
            'detalle_cita.min' => 'Debes agregar al menos un servicio a la cita.',
            'detalle_cita.*.tipo_servicio_id.required' => 'El tipo de servicio es obligatorio.',
            'detalle_cita.*.tipo_servicio_id.exists' => 'El tipo de servicio seleccionado no existe.',
            'detalle_cita.*.precio_capturado.required' => 'El precio capturado es obligatorio.',
            'detalle_cita.*.precio_capturado.numeric' => 'El precio capturado debe ser numérico.',
        ];
    }
}

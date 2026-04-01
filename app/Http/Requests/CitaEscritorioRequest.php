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
            'hora_c' => 'required|date_format:H:i',
            'fecha_c' => 'required|date',
            'estado' => 'sometimes|in:pendiente,confirmada,cancelada',
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
            'detalles.required' => 'Debes enviar al menos un detalle.',
            'detalles.array' => 'El detalle debe ser un arreglo.',
            'detalles.min' => 'Debes agregar al menos un servicio.',
            'detalles.*.servicio_id.required' => 'El servicio es obligatorio.',
            'detalles.*.servicio_id.exists' => 'El servicio seleccionado no existe.',
            'detalles.*.subtotal.required' => 'El subtotal es obligatorio.',
            'detalles.*.subtotal.numeric' => 'El subtotal debe ser numérico.',
        ];
    }
}
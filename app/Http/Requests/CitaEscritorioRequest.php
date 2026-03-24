<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaEscritorioRequest extends FormRequest
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
        'total' => 'required|numeric|min:0',
        'personal_id' => 'required|exists:personales,id',
        'hora_c' => 'required|date_format:H:i',
        'fecha_c' => 'required|date',
        'estado' => 'required|in:pendiente,confirmada,cancelada',
        'cliente_id' => 'required|exists:clientes,id',
    ];
    }

    public function messages()
    {
        return [
            'total.required' => 'El campo total es obligatorio.',
            'total.numeric' => 'El campo total debe ser un número.',
            'total.min' => 'El campo total debe ser al menos 0.',
            'personal_id.required' => 'El campo personal_id es obligatorio.',
            'personal_id.exists' => 'El personal_id debe existir en la tabla personales.',
            'hora_c.required' => 'El campo hora_c es obligatorio.',
            'hora_c.date_format' => 'El campo hora_c debe tener el formato H:i.',
            'fecha_c.required' => 'El campo fecha_c es obligatorio.',
            'fecha_c.date' => 'El campo fecha_c debe ser una fecha válida.',
            'estado.required' => 'El campo estado es obligatorio.',
            'estado.in' => 'El campo estado debe ser uno de los siguientes: pendiente, confirmada, cancelada.',
            'cliente_id.required' => 'El campo cliente_id es obligatorio.',
            'cliente_id.exists' => 'El cliente_id debe existir en la tabla clientes.',
        ];
    }


}

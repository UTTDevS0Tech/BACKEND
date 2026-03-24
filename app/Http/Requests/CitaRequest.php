<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaRequest extends FormRequest
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
            'apartado' => 'required|numeric|min:0',
            'personal_id' => 'required|exists:personales,id',
            'hora_c' => 'required|date_format:H:i',
            'fecha_c' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,cancelada',
            'cliente_id' => 'nullable|exists:clientes,id',
        ];
    }

     public function messages() {

        return [
            'apartado.required' => 'El apartado es obligatorio.',
            'personal_id.required' => 'El personal es obligatorio.',
            'hora_c.required' => 'La hora de la cita es obligatoria.',
            'fecha_c.required' => 'La fecha de la cita es obligatoria.',
            'estado.required' => 'El estado de la cita es obligatorio.',
            'cliente_id.required' => 'El cliente es obligatorio.',
            'personal_id.exists' => 'El personal seleccionado no existe.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ];
    }
}

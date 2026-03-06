<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicioRequest extends FormRequest
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
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:1000',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tiempo_estimado' => 'required|integer|min:1',
            'tipo_servicio_id' => 'required|exists:tipo_servicio,id',
        ];
    }

    public function messages() {
        return [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'precio.required' => 'El precio del servicio es obligatorio.',
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
            'imagen.image' => 'La imagen debe ser un archivo de imagen válido.',
            'tiempo_estimado.required' => 'El tiempo estimado es obligatorio.',
            'tipo_servicio_id.required' => 'El tipo de servicio es obligatorio.',
            'tipo_servicio_id.exists' => 'El tipo de servicio seleccionado no existe.',
        ];
    }
}


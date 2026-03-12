<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoServicioRequest extends FormRequest
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
        'servicio_id' => 'required|exists:servicios,id',
    ];
    }

public function messages()
{
    return [
        'nombre.required' => 'El nombre del tipo de servicio es obligatorio.',
        'precio.required' => 'El precio del tipo de servicio es obligatorio.',
        'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
        'imagen.image' => 'La imagen debe ser un archivo de imagen válido.',
        'tiempo_estimado.required' => 'El tiempo estimado es obligatorio.',
        'servicio_id.required' => 'El servicio es obligatorio.',
        'servicio_id.exists' => 'El servicio seleccionado no existe.',
    ];
}
}


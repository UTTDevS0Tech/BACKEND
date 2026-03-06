<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalRequest extends FormRequest
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
            'descripcion' => 'nullable|string|max:500',
            'user_id' => 'required|exists:users,id|unique:personales,user_id',
        ];
    }

    public function messages()
    {
        return [
          'nombre.required' => 'El nombre del personal es obligatorio.',
            'descripcion.max' => 'La descripción no puede exceder los 500 caracteres.',
            'user_id.required' => 'El usuario asociado es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'user_id.unique' => 'El usuario ya está asociado a otro personal.',
        ];
    }
}

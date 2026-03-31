<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $estilista = $this->route('estilista');
        $personalId = is_object($estilista) ? $estilista->id : $estilista;

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('personales', 'user_id')->ignore($personalId, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'user_id.unique' => 'El usuario ya está asociado a otro personal.',
        ];
    }
}
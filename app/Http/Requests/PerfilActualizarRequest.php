<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerfilActualizarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'sometimes|string|max:255',
            'apellido_p' => 'sometimes|string|max:255',
            'apellido_m' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.string' => 'El nombre debe ser texto.',
            'nom.max' => 'El nombre no debe exceder 255 caracteres.',
            'apellido_p.string' => 'El apellido paterno debe ser texto.',
            'apellido_p.max' => 'El apellido paterno no debe exceder 255 caracteres.',
            'apellido_m.string' => 'El apellido materno debe ser texto.',
            'apellido_m.max' => 'El apellido materno no debe exceder 255 caracteres.',
            'email.email' => 'El correo electronico no es valido.',
            'email.max' => 'El correo electronico no debe exceder 255 caracteres.',
            'email.unique' => 'Ese correo ya esta en uso.',
        ];
    }
}

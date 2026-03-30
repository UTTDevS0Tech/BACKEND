<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class PerfilActualizarRequest extends FormRequest
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
            'nom'=>'sometimes|string|max:255',
            'apellido_p'=>'sometimes|string|max:255',
            'apellido_m'=>'sometimes|string|max:255',
            'email'=>['sometimes',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'tel'=>'sometimes|string|max:15',
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
            'email.email' => 'El correo electrónico no es válido.',
            'email.max' => 'El correo electrónico no debe exceder 255 caracteres.',
            'email.unique' => 'Ese correo ya está en uso.',
            'tel.string' => 'El teléfono debe ser texto.',
            'tel.max' => 'El teléfono no debe exceder 15 caracteres.',
        ];
    }
}

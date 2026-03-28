<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRecepRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'apellido_p' => 'required|string|max:255',
            'apellido_m' => 'nullable|string|max:255',
            'tel' => 'nullable|string|max:15',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'El nombre es obligatorio.',
            'apellido_p.required' => 'El apellido paterno es obligatorio.',
            'nom.string' => 'El nombre debe ser texto.',
            'apellido_p.string' => 'El apellido paterno debe ser texto.',
            'apellido_m.string' => 'El apellido materno debe ser texto.',
            'tel.string' => 'El teléfono debe ser texto.',
            'tel.max' => 'El teléfono no debe exceder 15 caracteres.',
        ];
    }
}

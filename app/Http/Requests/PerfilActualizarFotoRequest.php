<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerfilActualizarFotoRequest extends FormRequest
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
        'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

        ];
    }

    public function messages(): array
    {
        return [
            'foto.required' => 'La foto es obligatoria.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser jpg, jpeg, png o webp.',
            'foto.max' => 'La imagen no debe superar los 2 MB.',
        ];
    }
}
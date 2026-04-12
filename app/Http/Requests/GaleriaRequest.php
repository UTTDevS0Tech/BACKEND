<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GaleriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'titulo' => 'required|string|max:255',
        'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:10000',
        'categoria_id' => 'required|exists:categorias_galeria,id',
    ];
}

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.string' => 'El título debe ser texto.',
            'titulo.max' => 'El título no debe exceder 255 caracteres.',

            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpg, jpeg, png o webp.',
            'imagen.max' => 'La imagen no debe pesar más de 10 MB.',

            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
        ];
    }
}
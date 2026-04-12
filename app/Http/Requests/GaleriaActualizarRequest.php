<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GaleriaActualizarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'titulo' => 'sometimes|string|max:255',
        'imagen' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:10000',
        'categoria_id' => 'sometimes|exists:categorias_galeria,id',
    ];
}

    public function messages(): array
    {
        return [
            'titulo.string' => 'El título debe ser texto.',
            'titulo.max' => 'El título no debe exceder 255 caracteres.',

            'imagen.image' => 'El archivo debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpg, jpeg, png o webp.',
            'imagen.max' => 'La imagen no debe pesar más de 10 MB.',
            //para puchear
        ];
    }

    public function withValidator($validator): void
{
    $validator->after(function ($validator) {
        $hasTitulo = $this->has('titulo');
        $hasImagen = $this->hasFile('imagen');
        $hasCategoria = $this->has('categoria_id');

        if (!$hasTitulo && !$hasImagen && !$hasCategoria) {
            $validator->errors()->add(
                'request',
                'Debes enviar al menos un campo para actualizar: título, imagen o categoría.'
            );
        }
    });
}
}
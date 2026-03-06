<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
        'tel' => 'required|string|max:15',
        'user_id' => 'nullable|exists:users,id|unique:clientes,user_id',
        'email' => 'nullable|email|max:255' 
            
        ];
    }
}


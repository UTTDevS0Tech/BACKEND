<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
   {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'user_id' => $this->user_id,
            //falta lo de horario desmenuzado 
            'horarios' => $this->horarios->map(function($ho) {
                return [
                    'dia' => $ho->dia_semana,
                    'inicio'=> $ho->hora_inicio,
                    'fin'=> $ho->hora_fin,
                    'activo'=>(bool)$ho->activo

                ];
            }),
        ];
    }
}

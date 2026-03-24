<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
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
        'apartado' => $this->apartado,
        'personal_id' => $this->personal->nombre,
        'horax_c' => $this->hora_c,
        'fecha_c' => $this->fecha_c,
        'estado' => $this->estado,
        'cliente_id' => $this->cliente->nom,
        ];
    }
}

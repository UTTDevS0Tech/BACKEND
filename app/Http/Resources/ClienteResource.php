<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
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
            'nom' => $this->nom,
            'apellido_p' => $this->apellido_p,
            'apellido_m' => $this->apellido_m,
            'telefono' => $this->telefono,
            'citas' => CitaResource::collection($this->citas)//aqui podriamos meter el whenloaded


        ];
    }
}

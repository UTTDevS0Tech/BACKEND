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
        'total' => $this->total,
        'personal_id' => $this->personal->nombre,
        'horax_c' => $this->hora_c,
        'fecha_c' => $this->fecha_c,
        'estado' => $this->estado,
        'cliente_id' => $this->cliente->nom,

        'cliente'=>$this->whenLoaded('cliente', function () {
            return [
                'id' => $this->cliente->id,
                'nom' => $this->cliente->nom,
                'apellido_p' => $this->cliente->apellido_p,
                'apellido_m' => $this->cliente->apellido_m,
                'nombre_completo'=>trim("{$this->cliente->nom} {$this->cliente->apellido_p} {$this->cliente->apellido_m}"),
                'tel' => $this->cliente->tel,
            ];
        }),

        ];
    }
}

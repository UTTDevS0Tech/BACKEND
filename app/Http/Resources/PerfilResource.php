<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfilResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'cliente' => $this->whenLoaded('cliente', function () {
                return [
                    'nom' => $this->cliente->nom,
                    'apellido_p' => $this->cliente->apellido_p,
                    'apellido_m' => $this->cliente->apellido_m,
                    'nombre_completo' => trim("{$this->cliente->nom} {$this->cliente->apellido_p} {$this->cliente->apellido_m}"),
                ];
            }),
        ];
    }
}

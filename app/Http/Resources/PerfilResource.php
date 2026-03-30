<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfilResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'activo' => $this->activo,
            'rol_id' => $this->rol_id,
            'rol' => $this->whenLoaded('rol', function () {
                return [
                    'id' => $this->rol->id,
                    'nom' => $this->rol->nom,
                ];
            }),
            'cliente' => $this->whenLoaded('cliente', function () {
                return [
                    'id' => $this->cliente->id,
                    'nom' => $this->cliente->nom,
                    'apellido_p' => $this->cliente->apellido_p,
                    'apellido_m' => $this->cliente->apellido_m,
                    'nombre_completo' => trim("{$this->cliente->nom} {$this->cliente->apellido_p} {$this->cliente->apellido_m}"),
                    'tel' => $this->cliente->tel,
                    'foto' => $this->cliente->foto,
                    'foto_url' => $this->cliente->foto
                        ? asset('storage/' . $this->cliente->foto)
                        : null,
                ];
            }),
        ];
    }
}
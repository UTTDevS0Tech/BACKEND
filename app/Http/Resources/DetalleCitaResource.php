<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleCitaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'servicio' => $this->tipoServicio?->nombre ?? 'Servicio desconocido',
            'precio_capturado' => $this->precio_capturado,
        ];
    }
}
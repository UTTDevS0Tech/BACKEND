<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleCitaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo_servicio_id' => $this->tipo_servicio_id,
            'precio_capturado' => $this->precio_capturado,
        ];
    }
}

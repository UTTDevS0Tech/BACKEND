<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DetalleCitaResource;

class CitaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apartado' => $this->apartado,
            'total' => $this->total,
            'personal' => $this->personal?->nombre ?? 'sin asignar',
            'hora_c' => $this->hora_c,
            'hora_fin' => $this->hora_fin,
            'fecha_c' => $this->fecha_c,
            'estado' => $this->estado,
            'cliente' => trim(($this->cliente?->nom ?? '') . ' ' .($this->cliente?->apellido_p ?? '') . ' ' .($this->cliente?->apellido_m ?? '')) ?: 'desconocido',
            'detalle_cita' => DetalleCitaResource::collection($this->whenLoaded('detalles')),
        ];
    }
}
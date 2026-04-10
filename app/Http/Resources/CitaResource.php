<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apartado' => $this->apartado,
            'total' => $this->total,
            'personal_id' => $this->personal_id,
            'personal' => $this->personal?->nombre ?? 'sin asignar',
            'hora_c' => $this->hora_c,
            'hora_fin' => $this->hora_fin,
            'fecha_c' => $this->fecha_c,
            'estado' => $this->estado,
            'cliente_id' => $this->cliente_id,
            'cliente' => trim(
                ($this->cliente?->nom ?? '') . ' ' .
                ($this->cliente?->apellido_p ?? '') . ' ' .
                ($this->cliente?->apellido_m ?? '')
            ) ?: 'desconocido',
            'detalles' => $this->detalles->map(function ($detalle) {
                return [
                    'servicio_id' => $detalle->tipo_servicio_id,
                    'subtotal' => (float) ($detalle->precio_capturado ?? $detalle->subtotal ?? 0),
                    'servicio' => $detalle->tipoServicio?->nombre,
                ];
            })->values(),
        ];
    }
}

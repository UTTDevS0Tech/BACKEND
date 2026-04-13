<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dia_semana' => $this->dia_semana,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'activo' => (bool) $this->activo,
            'personal_id' => $this->personal_id,
        ];
    }
}

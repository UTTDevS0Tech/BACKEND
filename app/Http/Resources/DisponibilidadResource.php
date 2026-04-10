<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisponibilidadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'personal_id' => $this->resource,
            'fecha' => $this->Carbon::parse($this->resource)->format('g:i A'),
         
        ];
    }
}

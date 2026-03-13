<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'activo' => $this->activo,
            'rol_id' => $this->rol_id,
            'created_at' => $this->created_at,
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
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
            'nom' => $this->nom,
            'apellido_p' => $this->apellido_p,
            'apellido_m' => $this->apellido_m,
            //esto es para facilitar el rollo en el frontend y no tener que concatenar el nombre completo a cada rato 
            'nombre_completo'=>trim("{$this->nom} {$this->apellido_p} {$this->apellido_m}"),
            'tel' => $this->tel,
            'user_id' => $this->user_id,
            //aqui podriamos meter el whenloaded
            // Ya lo metí amiguito :D
            //el whenloaded es para cargar la relacion solo cuando se 
            // haya cargado previamente en el controlador, asi evitamos 
            // cargarla innecesariamente y optimizamos el rendimiento 
            'citas' => $this->whenLoaded('citas', function () {
                return $this->citas->map(function($cita){
                    return [
                        'id' => $cita->id,
                        'fecha_c' => $cita->fecha_c,
                        'hora_c' => $cita->hora_c,
                        'personal_id' => $cita->personal_id,
                        'apartado' => $cita->apartado,
                        'total' => $cita->total,
                    ];

                });
            }),
        ];
    }
}

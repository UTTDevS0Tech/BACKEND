<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Models\Cita;
use App\Http\Requests\CitaEscritorioRequest;
use App\Http\Resources\CitaResource;

class CitaEscritorioController extends Controller
{
    // lo mismo que con dieguito, este controller solo guarda la cita, no el detallecita, hay que checar eso en el store 
    use ApiResponse;

    public function index()
    {
        $citas = Cita::where('apartado', 0)
            ->whereNotNull('total')
            ->get();

        return $this->apiResponse(
            CitaResource::collection($citas),
            'Citas de escritorio obtenidas exitosamente'
        );
    }

    public function store(CitaEscritorioRequest $request)
    {
        $data = $request->validated();
        $data['apartado'] = 0;

        $cita = Cita::create($data);

        return $this->apiResponse(
            new CitaResource($cita),
            'Cita de escritorio creada correctamente',
            201
        );
    }

    public function show($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(
                null,
                'Cita de escritorio no encontrada',
                404
            );
        }

        return $this->apiResponse(
            new CitaResource($cita),
            'Cita de escritorio obtenida exitosamente'
        );
    }

    public function update(CitaEscritorioRequest $request, $id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(
                null,
                'Cita de escritorio no encontrada',
                404
            );
        }

        $data = $request->validated();
        $data['apartado'] = 0;

        $cita->update($data);

        return $this->apiResponse(
            new CitaResource($cita),
            'Cita de escritorio actualizada correctamente'
        );
    }

    public function destroy($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(
                null,
                'Cita de escritorio no encontrada',
                404
            );
        }

        $cita->delete();

        return $this->apiResponse(
            null,
            'Cita de escritorio eliminada correctamente'
        );
    }
}   
 


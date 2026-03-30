<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicioRequest;
use App\Http\Resources\ServicioResource;
use App\Models\Servicio;
use App\Traits\ApiResponse;

class ServicioController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $servicios = Servicio::all();

        return $this->successResponse(
            ServicioResource::collection($servicios),
            'Servicios obtenidos correctamente.',
            200
        );
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);

        return $this->successResponse(
            new ServicioResource($servicio),
            'Servicio obtenido correctamente.',
            200
        );
    }

    public function store(ServicioRequest $request)
    {
        $servicio = Servicio::create($request->validated());

        return $this->successResponse(
            new ServicioResource($servicio),
            'Servicio creado correctamente.',
            201
        );
    }

    public function update(ServicioRequest $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $servicio->update($request->validated());

        return $this->successResponse(
            new ServicioResource($servicio),
            'Servicio actualizado correctamente.',
            200
        );
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        $servicio->delete();

        return $this->successResponse(
            null,
            'Servicio eliminado correctamente.',
            200
        );
    }

    public function toggle($id)
    {
        $servicio = Servicio::findOrFail($id);

        $servicio->activo = !$servicio->activo;
        $servicio->save();

        if (!$servicio->activo) {
            $servicio->tiposServicio()->update([
                'activo' => false
            ]);
        }

        return $this->successResponse(
            new ServicioResource($servicio),
            'Estado del servicio actualizado correctamente.',
            200
        );
    }
}
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

        return $this->apiResponse(
            ServicioResource::collection($servicios));
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);

        return $this->apiResponse(
            new ServicioResource($servicio));
    }

    public function store(ServicioRequest $request)
    {
        $servicio = Servicio::create($request->validated());

        return $this->apiResponse(
            new ServicioResource($servicio));
    }

    public function update(ServicioRequest $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $servicio->update($request->validated());

        return $this->apiResponse(
            new ServicioResource($servicio));
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        $servicio->delete();

        return $this->apiResponse(
            null,
            'Servicio eliminado correctamente',
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

        return $this->apiResponse(
            new ServicioResource($servicio));
    }
}
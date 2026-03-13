<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicioRequest;
use App\Http\Resources\ServicioResource;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    
public function index()
{
    $servicios = Servicio::all();

    return ServicioResource::collection($servicios);
}

public function show($id)
{
    $servicio = Servicio::findOrFail($id);

    return new ServicioResource($servicio);
}

public function store(ServicioRequest $request)
{
    $servicio = Servicio::create($request->validated());

    return new ServicioResource($servicio);
}

public function update(ServicioRequest $request, $id)
{
    $servicio = Servicio::findOrFail($id);

    $servicio->update($request->validated());

    return new ServicioResource($servicio);
}
    
public function destroy($id)
{
    $servicio = Servicio::findOrFail($id);

    $servicio->delete();

    return response()->json([
        'message' => 'Servicio eliminado correctamente'
    ]);
}

public function toggle($id)
{
    $servicio = Servicio::findOrFail($id);

    // cambiar estado
    $servicio->activo = !$servicio->activo;
    $servicio->save();

    //desactivar todos los tipos de servicio
    if (!$servicio->activo) {
        $servicio->tiposServicio()->update([
            'activo' => false
        ]);
    }

    return response()->json([
        'message' => 'Estado del servicio actualizado correctamente',
        'data' => new ServicioResource($servicio)
    ]);
}
}
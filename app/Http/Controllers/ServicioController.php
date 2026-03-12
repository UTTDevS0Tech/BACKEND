<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // GET /api/servicio
    public function index()
    {
        $tipos = Servicio::all();

        return response()->json($tipos, 200);
    }

    // GET /api/servicio/{id}
    public function show($id)
    {
        $tipo = Servicio::find($id);

        if (!$tipo) {
            return response()->json([
                'message' => 'Servicio no encontrado'
            ], 404);
        }

        return response()->json($tipo, 200);
    }

    // POST /api/servicio
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'activo' => 'boolean'
        ]);

        $tipo = Servicio::create([
            'nombre' => $request->nombre,
            'activo' => $request->activo ?? true
        ]);

        return response()->json([
            'message' => 'Servicio creado correctamente',
            'data' => $tipo
        ], 201);
    }

    // PUT /api/servicio/{id}
    public function update(Request $request, $id)
    {
        $tipo = Servicio::find($id);

        if (!$tipo) {
            return response()->json([
                'message' => 'Servicio no encontrado'
            ], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'activo' => 'sometimes|boolean'
        ]);

        $tipo->update($request->only(['nombre', 'activo']));

        return response()->json([
            'message' => 'Servicio actualizado correctamente',
            'data' => $tipo
        ], 200);
    }

    // DELETE /api/servicio/{id}
    public function destroy($id)
    {
        $tipo = Servicio::find($id);

        if (!$tipo) {
            return response()->json([
                'message' => 'Servicio no encontrado'
            ], 404);
        }

        $tipo->delete();

        return response()->json([
            'message' => 'Servicio eliminado correctamente'
        ], 200);
    }

 public function toggle($id)
{
    $servicio = Servicio::find($id);

    if (!$servicio) {
        return response()->json([
            'message' => 'Servicio no encontrado'
        ], 404);
    }

    // Cambiar estado
    $servicio->activo = !$servicio->activo;
    $servicio->save();

    // Desactivar todos los tipos
    if (!$servicio->activo) {
        $servicio->tiposServicio()->update([
            'activo' => false
        ]);
    }

    return response()->json([
        'message' => 'Estado actualizado correctamente',
        'data' => $servicio
    ]);
}    
}
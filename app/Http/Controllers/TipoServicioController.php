<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TipoServicioRequest;


class TipoServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = TipoServicio::all();
        return response()->json($tipos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoServicioRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('tipo_servicios', 'public');
        }

        $tipoServicio = TipoServicio::create($data);

        return response()->json($tipoServicio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tipoServicio = TipoServicio::findOrFail($id);
        return response()->json($tipoServicio);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoServicioRequest $request, $id)
    {
        $tipoServicio = TipoServicio::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('tipo_servicios', 'public');
        }

        $tipoServicio->update($data);

        return response()->json($tipoServicio);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tipoServicio = TipoServicio::findOrFail($id);
        $tipoServicio->delete();

        return response()->json([
            'message' => 'Tipo de servicio eliminado correctamente'
        ]);
    }

    public function toggleStatus($id)
    {
        $tipoServicio = Tipo_Servicio::findOrFail($id);

        $tipoServicio->activo = !$tipoServicio->activo;
        $tipoServicio->save();

        return $this->apiResponse(
            $tipoServicio,
            'Estado del tipo de servicio actualizado correctamente'
        );
    }
}

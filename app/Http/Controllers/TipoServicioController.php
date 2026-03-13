<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TipoServicioRequest;
use App\Models\TipoServicio;
use App\Http\Resources\TipoServicioResource;
use App\Traits\ApiResponse;


class TipoServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;

    public function index()
    {

      $tipoServicios = TipoServicio::all();
      return $this->apiResponse(TipoServicioResource::collection(TipoServicio::all()));
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
        return $this->apiResponse(new TipoServicioResource($tipoServicio), 'Tipo de servicio creado correctamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tipoServicio = TipoServicio::findOrFail($id);
   
        if (!$tipoServicio) {
            return $this->apiResponse(null, 'Tipo de servicio no encontrado', 404);
        }
        return $this->apiResponse(new TipoServicioResource($tipoServicio), 'tipo de servicio encontrado');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoServicioRequest $request, $id)
    {
        $tipoServicio = TipoServicio::findOrFail($id);
            if (!$tipoServicio) {
                return $this->apiResponse(null, 'Tipo de servicio no encontrado', 404);
            }


        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('tipo_servicios', 'public');
        }

        $tipoServicio->update($data);

        return $this->apiResponse(new TipoServicioResource($tipoServicio), 'Tipo de servicio actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tipoServicio = TipoServicio::findOrFail($id);
        if (!$tipoServicio) {
            return $this->apiResponse(null, 'Tipo de servicio no encontrado', 404);
        }
        $tipoServicio->delete();

        return $this->apiResponse(null, 'Tipo de servicio eliminado correctamente');
    }

    public function toggleStatus($id)
    {
        $tipoServicio = TipoServicio::findOrFail($id);
        if (!$tipoServicio) {
            return $this->apiResponse(null, 'Tipo de servicio no encontrado', 404);
        }

        $tipoServicio->activo = !$tipoServicio->activo;
        $tipoServicio->save();

        return $this->apiResponse(new TipoServicioResource($tipoServicio), $tipoServicio->activo ? 
        'Tipo de servicio activado correctamente' : 'Tipo de servicio desactivado correctamente');
    }
}

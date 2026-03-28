<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Http\Resources\CitaResource;
use App\Traits\ApiResponse;
use Illuminate\Supports\Facades\Auth;
use App\Http\Requests\CitaRequest;
use App\Http\Requests\CitaUpdateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CitaController extends Controller
{
    use ApiResponse;
    

    public function index() {
        return $this->apiResponse(CitaResource::collection(Cita::all()), 'Citas regresadas', 200);
    }
/*
    public function store() {

        $data = $request->validated();
        $cita = Cita::create($data);
        return $this->apiResponse(new CitaResource($cita), 'Cita creada', 201);
    }
        */

    public function show ($id) {
        

        $cita = Cita::with(['cliennte', 'personal', 'servicio'])->find($id);
        if($id) {
            return $this->apiResponse(new CitaResource($id), 'Cita regresada', 200);
        } else {
            return $this->apiResponse(null, 'Cita no encontrada', 404);

        }
    }

    public function update($id) {
        $cita = Cita::find($id);
        if($cita) {
            $data = $request->validated();
            $cita->update($data);
            return $this->apiResponse(new CitaResource($cita), 'Cita actualizada', 200);
        } else {
            return $this->apiResponse(null, 'Cita no encontrada', 404);

        }
    }


    public function destroy($id) {
        $cita = Cita::find($id);
        if($cita) {
            $data = $request->validated();
            $cita->softdelete($data);
            return $this->apiResponse('cita borrada chido', 200);
        } else {
            return $this->apiResponse('cita no encontrada', 404);
        }
    }
//jalaraa??? quien sabe...
    public function store(CitaRequest $request) {

        $clienteabuscar = Cliente::where('user_id', Auth::id())->first();

        if(!$clienteabuscar) {
            return $this->apiResponse(null, 'no hay perfil', 404);

        }

        $data = $request->validated();
        $servicios = $data['detalle_cita'];

        unset($data['detalle_cita']); //no se la compliquen es basicemten por q no hay campo detalle_cita pero ocupas guardar los servicios entonces es como "ey guardamos los servicios pero al llegar a la cita es como "compare tu no tienes el campo" okay entonces bay bay y ya se los metemos a la detalle_citas
        $data['cliente_id'] = $clienteabuscar->id;
        $data['estado'] = 'pendiente';


    $citaweb = Cita::create($data);

    $citaweb->detalles()->createMany($servicios);
        return $this->apiResponse(new CitaResource($citaweb), 'cita web encontrada', 201);
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Cliente;
use App\Http\Resources\CitaResource;
use Carbon\Carbon;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CitaRequest;
use App\Http\Requests\CitaUpdateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\TipoServicio;


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
        

        $cita = Cita::with(['cliente', 'personal', 'servicio'])->find($id);
        if($cita) {
            return $this->apiResponse(new CitaResource($cita), 'Cita regresada', 200);
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

        return DB::transaction(function() use ($request){
        $clienteabuscar = Cliente::where('user_id', Auth::id())->first();

        if(!$clienteabuscar) {
            return $this->apiResponse(null, 'no hay perfil', 404);

        }

        $horaInicio = Carbon::parse($request->hora_c);

        $minutosTotales = collect($request->detalle_cita)->reduce(function ($api, $item){
            $servicio = TipoServicio::find($item['tipo_servicio_id']);
            return $api + ($int)($servicio->tiempo_duracion ?? 0    );
        }, 0);

        $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);






        $chocaonochocaconotrohorario = Cita::where('personal_id', $request->persona_id)
        ->where('fecha_c', $request->fecha_c)
        ->where('estado', '!=', 'cancelada')
        ->where(function($query) use ($horaInicio, $horaFin){
            $query->whereBetween('hora_c', [$horaInicio->format('H:i'), $horaFin->format('H:i')])
            ->orWhereBetween('hora_fin', [$horaInicio->format('H:i'), $horaFin->format('H:i')]);
        })
        
        ->exists();

        if($chocaonochocaconotrohorario) {
            return $this->apiResponse(null, 'ese horario ya ta busy compare');
        }
        $data = $request->validated();
        $servicios = $data['detalle_cita'];

        unset($data['detalle_cita']); //no se la compliquen es basicemten por q no hay campo detalle_cita pero ocupas guardar los servicios entonces es como "ey guardamos los servicios pero al llegar a la cita es como "compare tu no tienes el campo" okay entonces bay bay y ya se los metemos a la detalle_citas
        $data['cliente_id'] = $clienteabuscar->id;
        $data['estado'] = 'pendiente';
        $data['hora_fin'] = $horaFin->format('H:i');


    $citaweb = Cita::create($data);

    $citaweb->detalles()->createMany($servicios);
        return $this->apiResponse(new CitaResource($citaweb), 'cita web encontrada', 201);
    });
    }
}


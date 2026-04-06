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
// no jala vro :'''''v


    public function store(CitaRequest $request) {
try {
        return DB::transaction(function() use ($request){//esto es unicamente para que o se mande la cita completa o no se mande nada
        $clienteabuscar = Cliente::where('user_id', Auth::id())->first(); //scamos el user_id del cliente para luego sacar su id y meterlo a la cita, esto es para que el cliente no tenga que mandar su id en la peticion, ademas de que asi evitamos que un cliente pueda mandar el id de otro cliente y crear citas a nombre de ese cliente, con esto nos aseguramos de que el cliente solo pueda crear citas a su nombre

        if(!$clienteabuscar) {
            return $this->apiResponse(null, 'no hay perfil', 404); //simple validacion no hay cliente(perfil) no hay cita 

        }

        $horaInicio = Carbon::parse($request->hora_c);
//convertimos la hora_c a un numero int para poderlo sumar facil
        $minutosTotales = collect($request->detalle_cita)->reduce(function ($api, $item){
   //aggarmos todo el tiempo estimado de cada serviicio para saber cuanto durara la cita        
            $servicio = TipoServicio::find($item['tipo_servicio_id']);

            return $api + (int)($servicio->tiempo_estimado ?? 0    );
        }, 0);

        $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);


        $chocaonochocaconotrohorario = Cita::where('personal_id', $request->personal_id)
        ->where('fecha_c', $request->fecha_c)
        ->where('estado', '!=', 'cancelada')
        ->where(function($query) use ($horaInicio, $horaFin){
            $query->where('hora_c', '<', $horaFin->format('H:i'))->where('hora_fin', '>', $horaInicio->format('H:i'));
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
        $data['total'] = $request->total;


    $citaweb = Cita::create($data);

    $citaweb->detalles()->createMany($servicios);
        return $this->apiResponse(new CitaResource($citaweb->load('detalles')), 'cita web encontrada', 201);
   });
    } catch (\Exception $e) {
       return response()->json([
        'message'=> $e->getMessage(),
        'line'=> $e->getLine(),
       ], 500);
 
    }
}

}
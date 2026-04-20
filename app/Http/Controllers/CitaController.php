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
use App\Models\Horario;
use App\Http\Requests\DisponibilidadRequest;
use App\Http\Resources\DisponibilidadResource;
use App\Http\Resources\TipoServicioResource;
use App\Http\Requests\TipoServicioRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\CitaConfirmadaMail;



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

/*
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

            $servicio = TipoServicio::find($item['tipo_servicio_id'])->where('activo', true)->first();
   //aggarmos todo el tiempo estimado de cada serviicio para saber cuanto durara la cita        
         

            if(!$servicio) {
            throw new \Exception('Tipo de servicio no encontrado');
            }

            return $api + (int)($servicio->tiempo_estimado ?? 0    );
        }, 0);

            $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);


        $inicioHora = $horaInicio->format('H:i');
        $finHora = $horaFin->format('H:i');

        $dias = [0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'];
        $numeroDias = Carbon::parse($request->fecha_c)->dayOfWeek;
        $nombreDia = $dias[$numeroDias];
        $diaDeLaSemana = Carbon::parse($request->fecha_c)->dayOfWeek;

        $horariositrabajaonotrabaja = Horario::where('personal_id', $request->personal_id)->where('dia_semana', $nombreDia)->where('activo', true)->first();


        if(!$horariositrabajaonotrabaja) {
            return $this->apiResponse(null, 'ese dia no trabaja', 400); // no tiene mucha cincia //validacion para saber si el estilista trabaja el dia que el cliente quiere la cita

        }

        if($horaInicio < $horariositrabajaonotrabaja->hora_inicio || $horaFin > $horariositrabajaonotrabaja->hora_fin) {
            return $this->apiResponse(null, 'ese horario no trabaja', 400); //validacion para saber si el estilista trabaja en el horario que el cliente quiere la cita

        }


    

        $chocaonochocaconotrohorario = Cita::where('personal_id', $request->personal_id)
        ->where('fecha_c', $request->fecha_c)
        ->where('estado', '!=', 'cancelada')
        ->where(function($query) use ($inicioHora, $finHora){
            $query->where('hora_c', '<', $finHora)->where('hora_fin', '>', $inicioHora);
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
    *///cabrones este esta bien por si la llegamnos a cagar

    public function store(CitaRequest $request)
{
    try {
        $citaweb = DB::transaction(function () use ($request) {//esto es unicamente para que o se mande la cita completa o no se mande nada

            $clienteabuscar = Cliente::where('user_id', Auth::id())->first(); //scamos el user_id del cliente para luego sacar su id y meterlo a la cita, esto es para que el cliente no tenga que mandar su id en la peticion, ademas de que asi evitamos que un cliente pueda mandar el id de otro cliente y crear citas a nombre de ese cliente, con esto nos aseguramos de que el cliente solo pueda crear citas a su nombre

            if (!$clienteabuscar) {
                throw new \Exception('no hay perfil'); //simple validacion no hay cliente(perfil) no hay cita 
            }

            $horaInicio = Carbon::parse($request->hora_c);

            if ($this->horarioYaPaso($request->fecha_c, $request->hora_c)) {
                throw new \Exception('esa hora ya pasó para el día de hoy');
            }
            //convertimos la hora_c a un numero int para poderlo sumar facil

            $minutosTotales = collect($request->detalle_cita)->reduce(function ($api, $item) {

                $servicio = TipoServicio::where('id', $item['tipo_servicio_id'])
                    ->where('activo', true)
                    ->first();

                //aggarmos todo el tiempo estimado de cada serviicio para saber cuanto durara la cita        

                if (!$servicio) {
                    throw new \Exception('Tipo de servicio no encontrado');
                }

                return $api + (int) ($servicio->tiempo_estimado ?? 0);
            }, 0);

            $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);

            $inicioHora = $horaInicio->format('H:i');
            $finHora = $horaFin->format('H:i');

            $dias = [0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'];
            $numeroDias = Carbon::parse($request->fecha_c)->dayOfWeek;
            $nombreDia = $dias[$numeroDias];
            $diaDeLaSemana = Carbon::parse($request->fecha_c)->dayOfWeek;

            $horariositrabajaonotrabaja = Horario::where('personal_id', $request->personal_id)
                ->where('dia_semana', $nombreDia)
                ->where('activo', true)
                ->first();

            if (!$horariositrabajaonotrabaja) {
                throw new \Exception('ese dia no trabaja'); //validacion para saber si el estilista trabaja el dia que el cliente quiere la cita
            }

            if ($horaInicio < $horariositrabajaonotrabaja->hora_inicio || $horaFin > $horariositrabajaonotrabaja->hora_fin) {
                throw new \Exception('ese horario no trabaja'); //validacion para saber si el estilista trabaja en el horario que el cliente quiere la cita
            }

            $chocaonochocaconotrohorario = Cita::where('personal_id', $request->personal_id)
                ->where('fecha_c', $request->fecha_c)
                ->where('estado', '!=', 'cancelada')
                ->where(function ($query) use ($inicioHora, $finHora) {
                    $query->where('hora_c', '<', $finHora)->where('hora_fin', '>', $inicioHora);
                })
                ->exists();

            if ($chocaonochocaconotrohorario) {
                throw new \Exception('ese horario ya ta busy compare');
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

            return $citaweb;
        });

        $citaweb->load([
            'cliente.user',
            'personal',
            'detalles.tipoServicio'
        ]);

        try {
            if ($citaweb->cliente?->user?->email) {
                Mail::to($citaweb->cliente->user->email)
                    ->send(new CitaConfirmadaMail($citaweb));
            }
        } catch (\Exception $mailError) {
            Log::error('Error enviando correo de cita', [
                'message' => $mailError->getMessage(),
                'line' => $mailError->getLine(),
            ]);
        }

        return $this->apiResponse(
            new CitaResource($citaweb),
            'cita web creada correctamente',
            201
        );

    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
        ], 500);
    }
}


private function horarioYaPaso(string $fecha, string $hora): bool{

    $timezone = 'America/Mexico_City';
    $fechaCita = Carbon::parse($fecha, $timezone);
    $ahora = Carbon::now($timezone);

    if (!$fechaCita->isSameDay($ahora)) {
        return false;
    }

    $momentoCita = Carbon::createFromFormat(
        'Y-m-d H:i',
        $fechaCita->format('Y-m-d') . ' ' . $hora,
        $timezone
    );

    return $momentoCita->lessThanOrEqualTo($ahora);
    
    }

public function getDisponibilidad(DisponibilidadRequest $request) {
  
    $personalId = $request->personal_id;



    $fecha = $request->fecha;


    $dias = [0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'];
    
    
    $diaDeLaSemana = Carbon::parse($fecha)->dayOfWeek;
    $numeroDias = Carbon::parse($fecha)->dayOfWeek;
    $nombreDia = $dias[$numeroDias];
    
    $horario = Horario::where('personal_id', $personalId)->where('dia_semana', $nombreDia)->where('activo', true)->first();

    if(!$horario) {
        return $this->apiResponse(null, 'ese dia no trabaja', 400);
    }

    $citasOcupadas = Cita::where('personal_id', $personalId)->where('fecha_c', $fecha)->where('estado', '!=', 'cancelada')->get(['hora_c', 'hora_fin']);
    $slots = [];
    $inicio = Carbon::parse($horario->hora_inicio);
    $fin = Carbon::parse($horario->hora_fin);

    while($inicio->copy()->addMinutes(30) <=$fin) {
        $horaPropuesta = $inicio->format('H:i');
        $horaYaPaso = $this->horarioYaPaso($fecha, $horaPropuesta);


        $estaOcupada = $citasOcupadas->contains(function($cita) use ($horaPropuesta) {
            return $horaPropuesta >= Carbon::parse($cita->hora_c)->format('H:i') && $horaPropuesta < Carbon::parse($cita->hora_fin)->format('H:i');
        });

        if(!$estaOcupada && !$horaYaPaso) {
         $slots[] = [
                'hora' => $horaPropuesta,
                'formato_12h' => $inicio->format('g:i A') 
            ];
        }

        $inicio->addMinutes(30);

}

return $this->apiResponse(DisponibilidadResource::collection($slots), 'disponibilidad obtenida', 200);
}


//perdón diego necesito moverle jajaja
public function misCitas()
{
    $cliente = Cliente::where('user_id', Auth::id())->first();

    if (!$cliente) {
        return $this->apiResponse(null, 'Perfil de cliente no encontrado', 404);
    }

    $citas = Cita::with(['cliente', 'personal', 'detalles.tipoServicio'])
        ->where('cliente_id', $cliente->id)
        ->orderByDesc('fecha_c')
        ->orderByDesc('hora_c')
        ->get();

    return $this->apiResponse(
        CitaResource::collection($citas),
        'Citas del cliente obtenidas correctamente',
        200
    );
}

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReagendarCitaRequest;
use App\Http\Requests\CitaEscritorioRequest;
use App\Http\Requests\ClienteRecepRequest;
use App\Http\Resources\CitaResource;
use App\Http\Resources\ClienteResource;
use App\Models\Cita;
use App\Models\Cliente;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RecepcionistaController extends Controller
{
    use ApiResponse;

   public function crearClient(ClienteRecepRequest $request){

        $data= $request->validated();
        $data['user_id'] = null;

        $cliente = Cliente::create($data);

        return $this->successResponse(new ClienteResource($cliente), 'Cliente creado exitosamente', 201);

   }

   public function buscarClientes(Request $request){
   
        $query = Cliente::query();

        if($request->filled('search')){
            $search =$request->search;

            $query->where(function($q) use ($search){
                $q->where('nom', 'like', "%{$search}%")
                ->orWhere('apellido_p', 'like', "%{$search}%")
                ->orWhere('apellido_m', 'like', "%{$search}%");
            });

        }
        $clientes = $query->latest()->paginate(10);
        return $this->successResponse(ClienteResource::collection($clientes), 'Clientes encontrados', 200);
   }


   public function crearCita(CitaEscritorioRequest $request){

   $data = $request->validated();
    // valida que la cita que se quiere crear no se sobreponga con otra cita del mismo personal en la misma fecha y hora, y que no esté cancelada
    $existsOverlappingCita=Cita::where('personal_id', $data['personal_id'])
    ->where('fecha_c', $data['fecha_c'])
    ->where('hora_c', $data['hora_c'])
    ->where('estado', '!=', 'cancelada')
    ->exists();

    if($existsOverlappingCita){
        return $this->errorResponse('El personal ya tiene una cita programada en esa fecha y hora', 409);
    }

    $data['estado'] = 'pendiente';
    $data['apartado'] = $data['apartado'] ?? 0;

    $cita = Cita::create($data);
    $cita->load('cliente', 'personal');

    return $this->successResponse(new CitaResource($cita), 'Cita creada exitosamente', 201);

   }

   

}

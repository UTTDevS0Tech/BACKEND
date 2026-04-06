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

   public function crearCliente(ClienteRecepRequest $request){

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
                $q->orWhereRaw("CONCAT(nom, ' ', apellido_p, ' ', apellido_m) LIKE ?",["%{$search}%"]);

            });

        }
        $clientes = $query->latest()->paginate(10);
        return $this->successResponse(ClienteResource::collection($clientes), 'Clientes encontrados', 200);
   }

   public function buscarCitasPorCliente(Request $request){
    $query = Cita::with(['cliente', 'personal']);

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->whereHas('cliente', function ($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('apellido_p', 'like', "%{$search}%")
              ->orWhere('apellido_m', 'like', "%{$search}%");
        });
    }

    $citas = $query
        ->orderBy('fecha_c', 'asc')
        ->orderBy('hora_c', 'asc')
        ->paginate(10);

    return $this->successResponse(
        CitaResource::collection($citas),
        'Citas encontradas',
        200
    );
}



}

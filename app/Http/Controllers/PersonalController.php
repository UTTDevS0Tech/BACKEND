<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use App\Http\Resources\PersonalResource;
use App\Traits\ApiResponse;
use App\Http\Requests\PersonalRequest;
use Illuminate\Support\Facades\DB;



class PersonalController extends Controller
{
    use ApiResponse;
    

    public function index() {
        $estilista = Personal::with('horarios')->whereHas('user.rol', function($query) {

        //aquí nomás le quité el rol_id y le puse id pq ya desde un inicio está el query en el rol al hacer user.rol :)
            $query -> where('id', '1'); 
        })->get();
            return $this->apiResponse(PersonalResource::collection($estilista),'aqui estan estilistas' ,200);
        }
        
    //regresa todos los personales, que regrese solo estilistas o hagan una función aparte para estilistas y otra para recepcionistas 


    public function store(PersonalRequest $request) {
        $data = $request->validated();
        $estilista = Personal::create($data);
        return $this->apiResponse(new PersonalResource($estilista), 'Estilista creado', 201);
    }

    public function show($id) {
        $estilista = Personal::find($id);
        if(!$estilista){
            return $this->apiResponse(null, 'Estilista no encontrado', 404);    

        } else {
            return $this->apiResponse(new PersonalResource($estilista), 'Estsilisat encontrado', 200);
        }


    }

    public function update(PersonalRequest $request, $id) {
    $personal = Personal::find($id);
    if($personal) {
        $data = $request->validated();
        $personal->update($data);
        return $this->apiResponse(new PersonalResource($personal), 'Estilista updateado chido', 200);
    } else {
        return $this->apiResponse(null, 'Personal no encontrado ni updeateado', 404);

    }
}

 
public function verMisCitasComoEstilista(Request $request)
{
    $userId = auth()->id();

    $query = DB::table('vista_mis_citas_estilista')
        ->where('user_id', $userId);

    if ($request->filled('categoria')) {
        $query->where('categoria_cita', $request->categoria);
    }

    if ($request->filled('dia')) {
        $query->where('dia_cita', $request->dia);
    }

    if ($request->filled('mes')) {
        $query->where('mes_cita', $request->mes);
    }

    if ($request->filled('anio')) {
        $query->where('anio_cita', $request->anio);
    }

    $citasencontradas = $query
        ->orderBy('fecha_c', 'asc')
        ->orderBy('hora_c', 'asc')
        ->get();

    if ($citasencontradas->isEmpty()) {
        return $this->apiResponse([], 'no se encontraron citas', 200);
    }

    return $this->apiResponse($citasencontradas, 'CITASS', 200);
}

}

    





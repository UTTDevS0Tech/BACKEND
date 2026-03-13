<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use App\Http\Resources\PersonalResource;
use App\Traits\ApiResponse;
use App\Http\Requests\PersonalRequest;


class PersonalController extends Controller
{
    use ApiResponse;
    

    public function index() {
        $estilistas = Personal::all();
        return $this->apiResponse(PersonalResource::collection($estilistas), 'Estilistas regresados', 200);
    }


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
        return $this->apiResponse(null, 'Personal no encontrado ni updeateado', 200);

    }
}



    }




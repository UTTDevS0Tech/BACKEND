<?php

namespace App\Http\Controllers;

use App\Http\Requests\HorarioSemanaRequest;
use App\Http\Resources\HorarioResource;
use App\Models\Horario;
use App\Models\Personal;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    use ApiResponse;

    public function index($personalId)
    {
        $personal = Personal::with('horarios')->find($personalId);

        if (!$personal) {
            return $this->errorResponse('Personal no encontrado', 404);
        }

        return $this->successResponse(
            HorarioResource::collection($personal->horarios),
            'Horarios obtenidos correctamente'
        );
    }

    public function guardarSemana(HorarioSemanaRequest $request, $personalId)
    {
        $personal = Personal::find($personalId);

        if (!$personal) {
            return $this->errorResponse('Personal no encontrado', 404);
        }

        $data = $request->validated();

        $horarios = DB::transaction(function () use ($personal, $data) {
            Horario::where('personal_id', $personal->id)->delete();

            $nuevosHorarios = collect($data['horarios'])->map(function ($horario) use ($personal) {
                return Horario::create([
                    'personal_id' => $personal->id,
                    'dia_semana' => $horario['dia_semana'],
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fin' => $horario['hora_fin'],
                    'activo' => $horario['activo'],
                ]);
            });

            return $nuevosHorarios;
        });

        return $this->successResponse(
            HorarioResource::collection($horarios),
            'Horarios guardados correctamente'
        );
    }
}


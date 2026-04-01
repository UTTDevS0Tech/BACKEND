<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Models\Cita;
use App\Models\DetalleCita;
use App\Models\Servicio; 
use App\Http\Requests\CitaEscritorioRequest;
use App\Http\Resources\CitaResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitaEscritorioController extends Controller
{
    use ApiResponse;

    public function store(CitaEscritorioRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $data = $request->validated();

            //valida todo el pedo
            if (!isset($data['detalles']) || empty($data['detalles'])) {
                return $this->apiResponse(null, 'Debes agregar al menos un servicio', 422);
            }

            $detalles = $data['detalles'];

            // 🔥 VALIDAR HORA (formato correcto)
            try {
                $horaInicio = Carbon::createFromFormat('H:i', $request->hora_c);
            } catch (\Exception $e) {
                return $this->apiResponse(null, 'Formato de hora inválido (usa HH:mm)', 422);
            }

            //checa q este en el rango
            $minutosTotales = collect($detalles)->reduce(function ($acum, $item) {

                if (!isset($item['servicio_id'])) {
                    abort(422, 'Servicio inválido');
                }

                $servicio = Servicio::find($item['servicio_id']); 

                if (!$servicio) {
                    abort(422, 'Servicio no existe');
                }

                return $acum + (int) ($servicio->tiempo_duracion ?? 0);

            }, 0);

            $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);

            //wacha si el estilista ya tiene una cita en ese horario
            $existe = Cita::where('personal_id', $request->personal_id)
                ->where('fecha_c', $request->fecha_c)
                ->where('estado', '!=', 'cancelada')
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where('hora_c', '<', $horaFin->format('H:i:s'))
                        ->where('hora_fin', '>', $horaInicio->format('H:i:s'));
                })
                ->exists();

            if ($existe) {
                return $this->apiResponse(null, 'Ese horario ya está ocupado', 409);
            }

            unset($data['detalles']);

            $data['apartado'] = 0;
            $data['hora_fin'] = $horaFin->format('H:i:s');

            $cita = Cita::create($data);

            //guarda los detalles de la cita
            foreach ($detalles as $detalle) {
                DetalleCita::create([
                    'cita_id' => $cita->id,
                    'servicio_id' => $detalle['servicio_id'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }

            return $this->apiResponse(
                new CitaResource($cita),
                'Cita creada correctamente',
                201
            );
        });
    }
}
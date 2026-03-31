<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Models\Cita;
use App\Models\DetalleCita;
use App\Models\TipoServicio;
use App\Http\Requests\CitaEscritorioRequest;
use App\Http\Resources\CitaResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitaEscritorioController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $citas = Cita::where('apartado', 0)
            ->whereNotNull('total')
            ->get();

        return $this->apiResponse(
            CitaResource::collection($citas),
            'Citas de escritorio obtenidas exitosamente'
        );
    }

    public function store(CitaEscritorioRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $data = $request->validated();

            $detalles = $data['detalles'];

            $horaInicio = Carbon::parse($request->hora_c);

            $minutosTotales = collect($request->detalles)->reduce(function ($acum, $item) {
                $servicio = TipoServicio::find($item['servicio_id']);
                return $acum + (int) ($servicio->tiempo_duracion ?? 0);
            }, 0);

            $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);

            $chocaonochocaconotrohorario = Cita::where('personal_id', $request->personal_id)
                ->where('fecha_c', $request->fecha_c)
                ->where('estado', '!=', 'cancelada')
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where('hora_c', '<', $horaFin->format('H:i:s'))
                        ->where('hora_fin', '>', $horaInicio->format('H:i:s'));
                })
                ->exists();

            if ($chocaonochocaconotrohorario) {
                return $this->apiResponse(null, 'ese horario ya ta busy compare', 409);
            }

            unset($data['detalles']);

            $data['apartado'] = 0;
            $data['hora_fin'] = $horaFin->format('H:i:s');

            $cita = Cita::create($data);

            foreach ($detalles as $detalle) {
                DetalleCita::create([
                    'cita_id' => $cita->id,
                    'servicio_id' => $detalle['servicio_id'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }

            return $this->apiResponse(
                new CitaResource($cita),
                'Cita de escritorio creada correctamente',
                201
            );
        });
    }

    public function show($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(
                null,
                'Cita de escritorio no encontrada',
                404
            );
        }

        return $this->apiResponse(
            new CitaResource($cita),
            'Cita de escritorio obtenida exitosamente'
        );
    }

    public function update(CitaEscritorioRequest $request, $id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(
                null,
                'Cita de escritorio no encontrada',
                404
            );
        }

        return DB::transaction(function () use ($request, $id, $cita) {

            $data = $request->validated();

            $detalles = $data['detalles'];

            $horaInicio = Carbon::parse($request->hora_c);

            $minutosTotales = collect($request->detalles)->reduce(function ($acum, $item) {
                $servicio = TipoServicio::find($item['servicio_id']);
                return $acum + (int) ($servicio->tiempo_duracion ?? 0);
            }, 0);

            $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);

            $chocaonochocaconotrohorario = Cita::where('personal_id', $request->personal_id)
                ->where('fecha_c', $request->fecha_c)
                ->where('estado', '!=', 'cancelada')
                ->where('id', '!=', $id)
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where('hora_c', '<', $horaFin->format('H:i:s'))
                        ->where('hora_fin', '>', $horaInicio->format('H:i:s'));
                })
                ->exists();

            if ($chocaonochocaconotrohorario) {
                return $this->apiResponse(null, 'ese horario ya ta busy compare', 409);
            }

            unset($data['detalles']);

            $data['apartado'] = 0;
            $data['hora_fin'] = $horaFin->format('H:i:s');

            $cita->update($data);

            DetalleCita::where('cita_id', $cita->id)->delete();

            foreach ($detalles as $detalle) {
                DetalleCita::create([
                    'cita_id' => $cita->id,
                    'servicio_id' => $detalle['servicio_id'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }

            return $this->apiResponse(
                new CitaResource($cita),
                'Cita de escritorio actualizada correctamente'
            );
        });
    }

    public function destroy($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(
                null,
                'Cita de escritorio no encontrada',
                404
            );
        }

        DetalleCita::where('cita_id', $cita->id)->delete();
        $cita->delete();

        return $this->apiResponse(
            null,
            'Cita de escritorio eliminada correctamente'
        );
    }
}
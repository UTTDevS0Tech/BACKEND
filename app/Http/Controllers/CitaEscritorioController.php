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
//le moví pa ver los detalles citas ok?
public function index()
{
    $citas = Cita::with(['cliente', 'personal', 'detalles.tipoServicio'])
        ->whereNotNull('total')
        ->get();

    return $this->apiResponse(
        CitaResource::collection($citas),
        'Citas obtenidas exitosamente',
        200
    );
}

    public function store(CitaEscritorioRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            if (!isset($data['detalles']) || empty($data['detalles'])) {
                return $this->apiResponse(null, 'Debes agregar al menos un servicio', 422);
            }

            $detalles = $data['detalles'];

            try {
                $horaInicio = Carbon::createFromFormat('H:i', $request->hora_c);
            } catch (\Exception $e) {
                return $this->apiResponse(null, 'Formato de hora inválido (usa HH:mm)', 422);
            }

            $minutosTotales = collect($detalles)->reduce(function ($acum, $item) {
                if (!isset($item['servicio_id'])) {
                    abort(422, 'Servicio inválido');
                }

                $tipoServicio = TipoServicio::find($item['servicio_id']);

                if (!$tipoServicio) {
                    abort(422, 'Tipo de servicio no existe');
                }

                return $acum + (int) ($tipoServicio->tiempo_estimado ?? 0);
            }, 0);

            $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);

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

            foreach ($detalles as $detalle) {
                DetalleCita::create([
                    'cita_id' => $cita->id,
                    'tipo_servicio_id' => $detalle['servicio_id'],
                    'precio_capturado' => $detalle['subtotal'],
                ]);
            }

            return $this->apiResponse(
                new CitaResource($cita),
                'Cita creada correctamente',
                201
            );
        });
    }

    public function show($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(null, 'Cita de escritorio no encontrada', 404);
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
            return $this->apiResponse(null, 'Cita de escritorio no encontrada', 404);
        }

        return DB::transaction(function () use ($request, $id, $cita) {
            $data = $request->validated();

            if (!isset($data['detalles']) || empty($data['detalles'])) {
                return $this->apiResponse(null, 'Debes agregar al menos un servicio', 422);
            }

            $detalles = $data['detalles'];

            try {
                $horaInicio = Carbon::createFromFormat('H:i', $request->hora_c);
            } catch (\Exception $e) {
                return $this->apiResponse(null, 'Formato de hora inválido (usa HH:mm)', 422);
            }

            $minutosTotales = collect($detalles)->reduce(function ($acum, $item) {
                if (!isset($item['servicio_id'])) {
                    abort(422, 'Servicio inválido');
                }

                $tipoServicio = TipoServicio::find($item['servicio_id']);

                if (!$tipoServicio) {
                    abort(422, 'Tipo de servicio no existe');
                }

                return $acum + (int) ($tipoServicio->tiempo_estimado ?? 0);
            }, 0);

            $horaFin = $horaInicio->copy()->addMinutes($minutosTotales);

            $existe = Cita::where('personal_id', $request->personal_id)
                ->where('fecha_c', $request->fecha_c)
                ->where('estado', '!=', 'cancelada')
                ->where('id', '!=', $id)
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

            $cita->update($data);

            DetalleCita::where('cita_id', $cita->id)->delete();

            foreach ($detalles as $detalle) {
                DetalleCita::create([
                    'cita_id' => $cita->id,
                    'tipo_servicio_id' => $detalle['servicio_id'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }

            return $this->apiResponse(
                new CitaResource($cita),
                'Cita actualizada correctamente'
            );
        });
    }

    public function destroy($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return $this->apiResponse(null, 'Cita de escritorio no encontrada', 404);
        }

        DetalleCita::where('cita_id', $cita->id)->delete();
        $cita->delete();

        return $this->apiResponse(null, 'Cita eliminada correctamente');
    }
}

public function confirmar($id)
{
    $cita = Cita::find($id);

    if (!$cita) {
        return $this->apiResponse(null, 'Cita no encontrada', 404);
    }

    if (in_array($cita->estado, ['cancelada', 'completada'])) {
        return $this->apiResponse(null, 'No se puede confirmar esta cita', 422);
    }

    $cita->estado = 'confirmada';
    $cita->save();

    return $this->apiResponse($cita, 'Cita confirmada correctamente', 200);
}

public function cancelar($id)
{
    $cita = Cita::find($id);

    if (!$cita) {
        return $this->apiResponse(null, 'Cita no encontrada', 404);
    }

    if ($cita->estado === 'completada') {
        return $this->apiResponse(null, 'No se puede cancelar una cita completada', 422);
    }

    $cita->estado = 'cancelada';
    $cita->save();

    return $this->apiResponse($cita, 'Cita cancelada correctamente', 200);
}

public function completar($id)
{
    $cita = Cita::find($id);

    if (!$cita) {
        return $this->apiResponse(null, 'Cita no encontrada', 404);
    }

    if ($cita->estado === 'cancelada') {
        return $this->apiResponse(null, 'No se puede completar una cita cancelada', 422);
    }

    $cita->estado = 'completada';
    $cita->save();

    return $this->apiResponse($cita, 'Cita completada correctamente', 200);
}

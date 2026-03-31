<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Models\Cita;
use App\Models\DetalleCita;
use App\Http\Requests\CitaEscritorioRequest;
use App\Http\Resources\CitaResource;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $detalles = $data['detalles'];
            unset($data['detalles']);

            $data['apartado'] = 0;

            $cita = Cita::create($data);

            foreach ($detalles as $detalle) {
                DetalleCita::create([
                    'cita_id' => $cita->id,
                    'servicio_id' => $detalle['servicio_id'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }

            DB::commit();

            return $this->apiResponse(
                new CitaResource($cita),
                'Cita de escritorio creada correctamente',
                201
            );
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->apiResponse(
                null,
                'Error al crear la cita de escritorio: ' . $e->getMessage(),
                500
            );
        }
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

        DB::beginTransaction();

        try {
            $data = $request->validated();

            $detalles = $data['detalles'];
            unset($data['detalles']);

            $data['apartado'] = 0;

            $cita->update($data);

            DetalleCita::where('cita_id', $cita->id)->delete();

            foreach ($detalles as $detalle) {
                DetalleCita::create([
                    'cita_id' => $cita->id,
                    'servicio_id' => $detalle['servicio_id'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }

            DB::commit();

            return $this->apiResponse(
                new CitaResource($cita),
                'Cita de escritorio actualizada correctamente'
            );
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->apiResponse(
                null,
                'Error al actualizar la cita de escritorio: ' . $e->getMessage(),
                500
            );
        }
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


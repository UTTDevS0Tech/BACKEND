<?php

namespace App\Http\Controllers;

use App\Http\Requests\GaleriaRequest;
use App\Http\Requests\GaleriaActualizarRequest;
use App\Http\Resources\GaleriaResource;
use App\Models\Galeria;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $imagenes = Galeria::latest()->paginate(10);

        return $this->successResponse(
            GaleriaResource::collection($imagenes),
            'Imágenes obtenidas correctamente.',
            200
        );
    }

    public function show($id)
    {
        $imagen = Galeria::find($id);

        if (!$imagen) {
            return $this->errorResponse(
                'Imagen no encontrada.',
                404
            );
        }

        return $this->successResponse(
            new GaleriaResource($imagen),
            'Imagen obtenida correctamente.',
            200
        );
    }

  public function store(GaleriaRequest $request)
{
    $rutaImagen = $request->file('imagen')->store('galeria', 'public');

    return response()->json([
        'ruta' => $rutaImagen,
        'ruta_absoluta' => storage_path('app/public/' . $rutaImagen),
        'existe' => file_exists(storage_path('app/public/' . $rutaImagen)),
    ]);
}

    public function update(GaleriaActualizarRequest $request, $id)
    {
        $imagen = Galeria::find($id);

        if (!$imagen) {
            return $this->errorResponse(
                'Imagen no encontrada.',
                404
            );
        }

        $data = $request->validated();
        $datosActualizar = [];

        if (array_key_exists('titulo', $data)) {
            $datosActualizar['titulo'] = $data['titulo'];
        }

        if ($request->hasFile('imagen')) {
            if ($imagen->imagen && Storage::disk('public')->exists($imagen->imagen)) {
                Storage::disk('public')->delete($imagen->imagen);
            }

            $datosActualizar['imagen'] = $request->file('imagen')->store('galeria', 'public');
        }

        $imagen->update($datosActualizar);

        return $this->successResponse(
            new GaleriaResource($imagen),
            'Imagen actualizada correctamente.',
            200
        );
    }

    public function destroy($id)
    {
        $imagen = Galeria::find($id);

        if (!$imagen) {
            return $this->errorResponse(
                'Imagen no encontrada.',
                404
            );
        }

        if ($imagen->imagen && Storage::disk('public')->exists($imagen->imagen)) {
            Storage::disk('public')->delete($imagen->imagen);
        }

        $imagen->delete();

        return $this->successResponse(
            null,
            'Imagen eliminada correctamente.',
            200
        );
    }
}
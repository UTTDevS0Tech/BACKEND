<?php

namespace App\Http\Controllers;

use App\Http\Requests\GalleryStoreRequest;
use App\Http\Requests\GalleryUpdateRequest;
use App\Http\Resources\GalleryResource;
use App\Models\Galeria;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $imagenes = Galeria::latest()->paginate(10);

        return $this->apiResponse(
            GaleriaResource::collection($imagenes),
            'Imágenes obtenidas correctamente.'
        );
    }

    public function store(GaleriaRequest $request)
    {
        $data = $request->validated();

        $rutaImagen = $request->file('imagen')->store('galeria', 'public');

        $imagen = Galeria::create([
            'titulo' => $data['titulo'],
            'imagen' => $rutaImagen,
        ]);

        return $this->apiResponse(
            new GaleriaResource($imagen),
            'Imagen subida correctamente.',
            201
        );
    }

    public function update(GaleriaActualizarRequest $request, $id)
    {
        $imagen = Galeria::find($id);

        if (!$imagen) {
            return $this->errorResponse('Imagen no encontrada.', 404);
        }

        $imagen->update([
            'titulo' => $request->validated()['titulo'],
        ]);

        return $this->apiResponse(
            new GaleriaResource($imagen),
            'Título actualizado correctamente.'
        );
    }

    public function destroy($id)
    {
        $imagen = Galeria::find($id);

        if (!$imagen) {
            return $this->errorResponse('Imagen no encontrada.', 404);
        }

        if ($imagen->imagen && Storage::disk('public')->exists($imagen->imagen)) {
            Storage::disk('public')->delete($imagen->imagen);
        }

        $imagen->delete();

        return $this->apiResponse(
            null,
            'Imagen eliminada correctamente.'
        );
    }
}
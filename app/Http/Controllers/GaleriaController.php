<?php

namespace App\Http\Controllers;

use App\Http\Requests\GaleriaRequest;
use App\Http\Requests\GaleriaActualizarRequest;
use App\Http\Resources\GaleriaResource;
use App\Models\Galeria;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoriaGaleriaResource;
use App\Models\CategoriaGaleria;

class GaleriaController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $imagenes = Galeria::with('categoria')->latest()->paginate(10);

        return $this->successResponse(
            GaleriaResource::collection($imagenes),
            'Imágenes obtenidas correctamente.',
            200
        );
    }

    public function show($id)
    {
        $imagen = Galeria::with('categoria')->find($id);

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
        $data = $request->validated();

        $rutaImagen = $request->file('imagen')->store('galeria', 'public');

        $imagen = Galeria::create([
        'titulo' => $data['titulo'],
        'imagen' => $rutaImagen,
        'categoria_id' => $data['categoria_id'],
    ]);

        return $this->successResponse(
            new GaleriaResource($imagen),
            'Imagen subida correctamente.',
            201
        );
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

        if (array_key_exists('categoria_id', $data)) {
            $datosActualizar['categoria_id'] = $data['categoria_id'];
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

   public function galeriaPublica()
    {
        $categorias = CategoriaGaleria::with(['imagenes' => function ($query) {
            $query->latest();
        }])->get();

        return $this->successResponse(
            CategoriaGaleriaResource::collection($categorias),
            'Galería pública obtenida correctamente.',
            200
        );
    }
}
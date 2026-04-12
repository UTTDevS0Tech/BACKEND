<?php

namespace App\Http\Controllers;

use App\Models\CategoriaGaleria;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class CategoriaGaleriaController extends Controller
{
    use ApiResponse;

    /**
     * Listar todas las categorías
     */
    public function index()
    {
        $categorias = CategoriaGaleria::orderBy('nombre')->get();

        return $this->successResponse(
            $categorias,
            'Categorías obtenidas correctamente.',
            200
        );
    }

    /**
     * Crear una nueva categoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias_galeria,nombre'
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Esta categoría ya existe.'
        ]);

        $categoria = CategoriaGaleria::create([
            'nombre' => $request->nombre
        ]);

        return $this->successResponse(
            $categoria,
            'Categoría creada correctamente.',
            201
        );
    }

    /**
     * (Opcional) Eliminar categoría
     */
    public function destroy($id)
    {
        $categoria = CategoriaGaleria::find($id);

        if (!$categoria) {
            return $this->errorResponse(
                'Categoría no encontrada.',
                404
            );
        }

        $categoria->delete();

        return $this->successResponse(
            null,
            'Categoría eliminada correctamente.',
            200
        );
    }
}
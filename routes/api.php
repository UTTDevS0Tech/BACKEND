<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TipoServicioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);


Route::apiResource('servicios', ServicioController::class);
//GET /api/servicios
//GET /api/servicios/{id}
//POST /api/servicios
//PUT /api/servicios/{id}
//DELETE /api/servicios/{id}
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\UserController;

//USER
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users', [UserController::class, 'store']);
Route::patch('users/{id}/toggle', [UserController::class, 'toggleActivo']);
Route::get('users/rol/{rol}', [UserController::class, 'usersByRol']);

//AUTH
Route::post('/register', [AuthController::class, 'register']);

//SERVICIOS
Route::apiResource('servicios', ServicioController::class);
//GET /api/servicios
//GET /api/servicios/{id}
//POST /api/servicios
//PUT /api/servicios/{id}
//DELETE /api/servicios/{id}
Route::patch('servicio/{id}/toggle', [ServicioController::class, 'toggle']);

//TIPO SERVICIOS
Route::apiResource('tipo-servicios', TipoServicioController::class);
//GET /api/tipo-servicios
//GET /api/tipo-servicios/{id}
//POST /api/tipo-servicios
//PUT /api/tipo-servicios/{id}
//DELETE /api/tipo-servicios/{id}
Route::patch('tipo-servicios/{id}/toggle-status', [TipoServicioController::class, 'toggleStatus']);

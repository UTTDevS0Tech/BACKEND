<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\CitaEscritorioController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\RecepcionistaController;


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
Route::post('/login', [AuthController::class, 'login']);

//SERVICIOS
Route::apiResource('servicios', ServicioController::class);
Route::patch('servicio/{id}/toggle', [ServicioController::class, 'toggle']);

//TIPO SERVICIOS
Route::apiResource('tipo-servicios', TipoServicioController::class);
Route::patch('tipo-servicios/{id}/toggle-status', [TipoServicioController::class, 'toggleStatus']);

// CITA
Route::apiResource('estilistas', PersonalController::class);
Route::middleware('auth:sanctum')->group(function () {
Route::apiResource('citas', CitaController::class);
});

// CITA ESCRITORIO
Route::apiResource('estilistas', PersonalController::class);
Route::middleware('auth:sanctum')->group(function () {
Route::apiResource('citas-escritorio', CitaEscritorioController::class);
});


// RUTAS FUNCIONES DE RECEPCIONISTA

    //Crear cliente
    Route::post('/crear-cliente', [RecepcionistaController::class, 'crearClient']);
    //Ver clientes
    Route::get('/ver-clientes', [RecepcionistaController::class, 'buscarClientes']);
    //Crear cita
    Route::post('/crear-cita', [RecepcionistaController::class, 'crearCita']);
    // Ver citas
    Route::get('/ver-citas', [RecepcionistaController::class, 'verCitas']);
    // Cancelar cita
    Route::patch('/cancelar-cita/{id}', [RecepcionistaController::class, 'cancelarCita']);
    // Reagendar cita
    Route::patch('/reagendar-cita/{id}', [RecepcionistaController::class, 'reagendarCita']);


//GALERIA
Route::get('galeria', [GaleriaController::class, 'index']);
Route::get('galeria/{id}', [GaleriaController::class, 'show']);
Route::post('galeria', [GaleriaController::class, 'store']);
Route::put('galeria/{id}', [GaleriaController::class, 'update']);
Route::delete('galeria/{id}', [GaleriaController::class, 'destroy']);

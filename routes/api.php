<?php

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
use App\Http\Controllers\PerfilController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// RUTAS PUBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/galeria', [GaleriaController::class, 'index']);
Route::get('/servicios', [ServicioController::class, 'index']);
Route::get('/tipo-servicios', [TipoServicioController::class, 'index']);

Route::get('/galeria/{id}', [GaleriaController::class, 'show']);


Route::apiResource('estilistas', PersonalController::class);

// ADMIN
Route::middleware(['auth:sanctum', 'role:Administrador'])->group(function () {
    Route::apiResource('servicios', ServicioController::class)->except(['index']);
    Route::patch('/servicios/{id}/toggle', [ServicioController::class, 'toggle']);

    Route::apiResource('tipo-servicios', TipoServicioController::class)->except(['index']);
    Route::patch('/tipo-servicios/{id}/toggle-status', [TipoServicioController::class, 'toggleStatus']);

    Route::apiResource('estilistas', PersonalController::class)->except(['index']);
    
    Route::get('/galeria', [GaleriaController::class, 'index']);
    Route::get('/galeria/{id}', [GaleriaController::class, 'show']);
    Route::post('/galeria', [GaleriaController::class, 'store']);
    Route::put('/galeria/{id}', [GaleriaController::class, 'update']);
    Route::delete('/galeria/{id}', [GaleriaController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::patch('/users/{id}/toggle', [UserController::class, 'toggleActivo']);
    Route::get('/users/rol/{rol}', [UserController::class, 'usersByRol']);
});

// RECEPCIONISTA
Route::middleware(['auth:sanctum', 'role:Recepcionista'])->group(function () {
    Route::post('/crear-cliente', [RecepcionistaController::class, 'crearCliente']);
    Route::get('/ver-clientes', [RecepcionistaController::class, 'buscarClientes']);
    Route::get('/buscar-citas-cliente', [RecepcionistaController::class, 'buscarCitasPorCliente']);
    Route::apiResource('citas-escritorio', CitaEscritorioController::class);
    Route::patch('/citas-escritorio/{id}/confirmar', [CitaEscritorioController::class, 'confirmar']);
    Route::patch('/citas-escritorio/{id}/cancelar', [CitaEscritorioController::class, 'cancelar']);
    Route::patch('/citas-escritorio/{id}/completar', [CitaEscritorioController::class, 'completar']);

});

// ESTILISTA
Route::middleware(['auth:sanctum', 'role:Estilista'])->group(function () {
    Route::get('/mis-citas', [PersonalController::class, 'misCitas']);
});

// CLIENTE
Route::middleware(['auth:sanctum', 'role:Cliente'])->group(function () {
    Route::get('/ver-perfil', [PerfilController::class, 'mostrarPerfil']);
    Route::patch('/editar-perfil', [PerfilController::class, 'editarPerfil']);
    Route::get('/citas/disponibilidad', [CitaController::class, 'getDisponibilidad']);
    Route::apiResource('citas', CitaController::class);
});

Route::get('/test-mail', [AuthController::class, 'testMail']);

//pedos de correo
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json([
        'message' => 'Correo verificado correctamente'
    ]);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return response()->json([
        'message' => 'Correo de verificación reenviado'
    ]);
})->middleware(['auth:sanctum', 'throttle:6,1']);
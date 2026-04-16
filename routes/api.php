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
use App\Http\Controllers\CategoriaGaleriaController;
use App\Http\Controllers\HorarioController;


// RUTAS PUBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/citas/disponibilidad', [CitaController::class, 'getDisponibilidad']);

Route::get('/categorias-galeria', [CategoriaGaleriaController::class, 'index']);

Route::get('/galeria/publica', [GaleriaController::class, 'galeriaPublica']);
Route::get('/galeria', [GaleriaController::class, 'index']);
Route::get('/galeria/{id}', [GaleriaController::class, 'show']);

Route::get('/servicios', [ServicioController::class, 'index']);
Route::get('/tipo-servicios', [TipoServicioController::class, 'index']);

Route::apiResource('estilistas', PersonalController::class);

// ADMIN
Route::middleware(['auth:sanctum', 'role:Administrador'])->group(function () {
    Route::apiResource('servicios', ServicioController::class)->except(['index']);
    Route::patch('/servicios/{id}/toggle', [ServicioController::class, 'toggle']);

    Route::apiResource('tipo-servicios', TipoServicioController::class)->except(['index']);
    Route::patch('/tipo-servicios/{id}/toggle-status', [TipoServicioController::class, 'toggleStatus']);

    Route::apiResource('estilistas', PersonalController::class)->except(['index']);

    Route::post('/categorias-galeria', [CategoriaGaleriaController::class, 'store']);
    Route::delete('/categorias-galeria/{id}', [CategoriaGaleriaController::class, 'destroy']);
    
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

    Route::get('/personales/{id}/horarios', [HorarioController::class, 'index']);
    Route::put('/personales/{id}/horarios', [HorarioController::class, 'guardarSemana']);

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
    Route::put('/clientes/{id}', [RecepcionistaController::class, 'actualizarCliente']);


});

// ESTILISTA
Route::middleware(['auth:sanctum', 'role:Estilista'])->group(function () {
    Route::get('/mis-citas', [PersonalController::class, 'misCitas']);
    Route::get('/verMisCitasComoEstilista', [PersonalController::class, 'verMisCitasComoEstilista']);
});

// CLIENTE
Route::middleware(['auth:sanctum', 'role:Cliente'])->group(function () {
    Route::get('/servicioss', [TipoServicioController::class, 'getActivosParaDiego']);
    Route::get('/ver-perfil', [PerfilController::class, 'mostrarPerfil']);
    Route::patch('/editar-perfil', [PerfilController::class, 'editarPerfil']);
    Route::apiResource('citas', CitaController::class);
    Route::get('/mis-citas', [CitaController::class, 'misCitas']);
    Route::post('/create-payment-intent', [\App\Http\Controllers\API\StripeController::class, 'createPaymentIntent']);

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
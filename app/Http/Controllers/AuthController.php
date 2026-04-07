<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Traits\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Notifications\VerifyEmailCustom;



class AuthController extends Controller
{

    use ApiResponse;

public function register(Request $request)
{
    $validatedData = $request->validate([
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'nom' => 'required|string|max:255',
        'apellido_p' => 'required|string|max:255',
        'apellido_m' => 'required|string|max:255',
        'tel' => 'nullable|string|max:20',
    ]);

    $user = User::create([
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'rol_id' => 3,
        'activo' => true,
    ]);

    $user->cliente()->create([
        'nom' => $validatedData['nom'],
        'apellido_p' => $validatedData['apellido_p'],
        'apellido_m' => $validatedData['apellido_m'],
        'tel' => $validatedData['tel'] ?? null,
    ]);

    $user->sendEmailVerificationNotification();

    return response()->json([
        'message' => 'Usuario registrado correctamente. Revisa tu correo 👀',
    ], 201);
}


public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (!auth()->attempt($credentials)) {
        return $this->errorResponse('Credenciales inválidas', 401);
    }

    $user = auth()->user();

    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->successResponse([
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => new UserResource($user),
    ], 'Inicio de sesión exitoso');
}

public function logout(Request $request)
{
    auth()->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Cerraste sesión fuga!']);

}

public function testMail()
{
    try {
        Mail::mailer('resend')->raw(' Este es un correo de prueba desde Estética Nova', function ($message) {
            $message->to('carlosdanielgrdz@gmail.com')
                    ->subject('Test Laravel + Resend');
        });

        return response()->json([
            'ok' => true,
            'mailer' => config('mail.default'),
            'message' => 'Correo enviado'
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

}
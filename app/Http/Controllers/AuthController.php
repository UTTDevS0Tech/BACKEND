<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Traits\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    use ApiResponse;

public function register(Request $request)
{
    
    $validatedData = $request->validate([
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
    ]);

    $user->sendEmailVerificationNotification();

    return response()->json(['message' => 'Usuario registrado. Revisa tu correo para verificar tu cuenta.',], 201);

}


public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (!auth()->attempt($credentials)) {
        return $this->errorResponse('Credenciales inválidas', 401);
    }

    $user = auth()->user();

    if (!$user->hasVerifiedEmail()) {
        auth()->logout();

        return $this->errorResponse('Debes verificar tu correo antes de iniciar sesión', 403);
    }

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
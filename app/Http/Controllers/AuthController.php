<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Traits\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;

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

    return response()->json(['message' => 'Usuario Registrado correctamente :D', 'user' => $user], 201);

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
        'user' => $user,
    ], 'Inicio de sesión exitoso');

   /* $credentials = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if (!auth()->attempt($credentials)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    $token = auth()->user()->createToken('auth_token')->plainTextToken;

    return response()->json(['message' => 'Inicio de sesión exitoso', 
    'token' => $token,
    'user' => auth()->user(),
    'token_type' => 'Bearer']);
    */

}

public function logout(Request $request)
{
    auth()->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Cerraste sesión fuga!']);

}

}
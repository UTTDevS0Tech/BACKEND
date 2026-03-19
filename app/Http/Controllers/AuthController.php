<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class AuthController extends Controller
{
    
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


public function login(Request $request)
{
    $credentials = $request->validate([
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

}

public function logout(Request $request)
{
    auth()->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Cerraste sesión fuga!']);

}


}
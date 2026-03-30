<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'No autenticado'
            ], 401);
        }
        $user->loadMissing('rol');
        if (! $user->rol) {
            return response()->json([
                'message' => 'El usuario no tiene rol asignado'
            ], 403);
        }
        if (! in_array($user->rol->nombre, $roles, true)) {
            return response()->json([
                'message' => 'No tienes permisos para acceder a este recurso'
            ], 403);
        }
 
        return $next($request);
    }
}

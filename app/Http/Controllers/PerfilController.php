<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerfilActualizarRequest;
use App\Http\Resources\PerfilResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    use ApiResponse;

    public function mostrarPerfil(Request $request)
    {
        $user = $request->user()->load(['rol', 'cliente']);

        return $this->successResponse(
            new PerfilResource($user),
            'Perfil obtenido correctamente.',
            200
        );
    }

    public function editarPerfil(PerfilActualizarRequest $request)
    {
        $user = $request->user()->load('cliente');
        $data = $request->validated();

        if (empty($data)) {
            return $this->errorResponse(
                'No se enviaron datos para actualizar.',
                422
            );
        }

        $userData = [];
        $clienteData = [];

        if (array_key_exists('email', $data)) {
            $userData['email'] = $data['email'];
        }

        if (array_key_exists('nom', $data)) {
            $clienteData['nom'] = $data['nom'];
        }

        if (array_key_exists('apellido_p', $data)) {
            $clienteData['apellido_p'] = $data['apellido_p'];
        }

        if (array_key_exists('apellido_m', $data)) {
            $clienteData['apellido_m'] = $data['apellido_m'];
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        if (!empty($clienteData)) {
        if (!$user->cliente) {
        return $this->errorResponse('No se encontro la informacion del cliente.', 404);
        }

    $user->cliente->update($clienteData);
}

        $user->load(['rol', 'cliente']);

        return $this->successResponse(
            new PerfilResource($user),
            'Perfil actualizado correctamente.',
            200
        );
    }
}

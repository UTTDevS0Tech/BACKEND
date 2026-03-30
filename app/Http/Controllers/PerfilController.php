<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerfilActualizarRequest;
use App\Http\Requests\PerfilActualizarFotoRequest;
use App\Http\Resources\PerfilResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
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

    public function actualizarPerfil(PerfilActualizarRequest $request)
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

        if (array_key_exists('tel', $data)) {
            $clienteData['tel'] = $data['tel'];
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        if (!empty($clienteData) && $user->cliente) {
            $user->cliente->update($clienteData);
        }

        $user->load(['rol', 'cliente']);

        return $this->successResponse(
            new PerfilResource($user),
            'Perfil actualizado correctamente.',
            200
        );
    }

    public function actualizarFoto(PerfilActualizarFotoRequest $request)
    {
        $user = $request->user()->load('cliente');

        if (!$user->cliente) {
            return $this->errorResponse(
                'No se encontró el perfil del cliente.',
                404
            );
        }

        if ($user->cliente->foto) {
            Storage::disk('public')->delete($user->cliente->foto);
        }

        $path = $request->file('foto')->store('clientes', 'public');

        $user->cliente->update([
            'foto' => $path,
        ]);

        $user->load(['rol', 'cliente']);

        return $this->successResponse(
            new PerfilResource($user),
            'Foto de perfil actualizada correctamente.',
            200
        );
    }
}
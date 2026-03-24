<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    // GET /users
    public function index()
    {
        $users = User::all();

        return $this->apiResponse(UserResource::collection($users),'Lista de usuarios');
    }

    // GET /users/{id}
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->apiResponse(null, 'Usuario no encontrado', 404);
        }

        return $this->apiResponse(
            new UserResource($user),
            'Usuario encontrado'
        );
    }

    // POST /users
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return $this->apiResponse(
            new UserResource($user),
            'Usuario creado correctamente',
            201
        );
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->apiResponse(null, 'Usuario no encontrado', 404);
        }

        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $this->apiResponse(
            new UserResource($user),
            'Usuario actualizado correctamente'
        );
    }

    public function toggleActivo($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->apiResponse(null, 'Usuario no encontrado', 404);
        }

        $user->activo = !$user->activo;
        $user->save();

        return $this->apiResponse(
            new UserResource($user),
            'Estado del usuario actualizado correctamente'
        );
    }

    public function usersByRol($rol)
    {
        $users = User::where('rol_id', $rol)->get();

        if ($users->isEmpty()) {
            return $this->apiResponse(null, 'No hay usuarios con ese rol', 404);
        }

        return $this->apiResponse(
            UserResource::collection($users),
            'Usuarios encontrados'
        );
    }
}
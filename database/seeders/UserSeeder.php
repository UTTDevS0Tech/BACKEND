<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
    User::create([
        'email' => 'estilista@estetica.com',
        'password' => Hash::make('password123'),
        'activo' => true,
        'rol_id' => Rol::where('nombre', 'Estilista')->first()->id,
    ]);

    

        User::create([
            'email' => 'cliente@gmail.com',
        'password' => Hash::make('password123'),
        'activo' => true,
        'rol_id' => Rol::where('nombre', 'Cliente')->first()->id,
        ]);
    }
}
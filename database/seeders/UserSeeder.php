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
        'email' => 'estilista@gmail.com',
        'password' => Hash::make('password123'),
        'activo' => true,
        'rol_id' => Rol::where('nombre', 'Estilista')->first()->id,
        'email_verified_at' => now(),
    ]);

    

        User::create([
            'email' => 'cliente@gmail.com',
        'password' => Hash::make('password123'),
        'activo' => true,
        'rol_id' => Rol::where('nombre', 'Cliente')->first()->id,
                'email_verified_at' => now(),

        ]);


        User::create([
            'email' => 'recepcionista@gmail.com',
        'password' => Hash::make('password123'),
        'activo' => true,
        'rol_id' => Rol::where('nombre', 'Recepcionista')->first()->id,
                'email_verified_at' => now(),

        ]);

                User::create([
            'email' => 'admin@gmail.com',
        'password' => Hash::make('password123'),
        'activo' => true,
        'rol_id' => Rol::where('nombre', 'Administrador')->first()->id,
                'email_verified_at' => now(),

        ]);
    }
}
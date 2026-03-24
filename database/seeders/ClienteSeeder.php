<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $usuario = User::where('email', 'cliente@gmail.com')->first();

        if($usuario) {
            Cliente::create([
            'nom' => 'Juan',
            'apellido_p' => 'Epa',
            'apellido_m' => 'Epale',
            'tel' => '9123121',
            'user_id' => $usuario->id,  
            
            ]);
       
    }

    Cliente::create([
        'nom' => 'Esc',
        'apellido_p' => 'Perez', // Evita poner basura como 'asdada' para que tus pruebas sean reales
        'apellido_m' => 'Garcia',
        'tel' => '91213121',
        'user_id' => null, // Aquí va null explícitamente o simplemente no lo pongas
    ]);


    }
}

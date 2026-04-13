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
            'nom' => 'Cliente',
            'apellido_p' => '#',
            'apellido_m' => '1',
            'tel' => '11111111',
            'user_id' => $usuario->id,  
            
            ]);
       
    }

    Cliente::create([
        'nom' => 'Cliente',
        'apellido_p' => '#', // Evita poner basura como 'asdada' para que tus pruebas sean reales
        'apellido_m' => '2',
        'tel' => '22222222',
        'user_id' => null, // Aquí va null explícitamente o simplemente no lo pongas
    
    ]);


    }
}

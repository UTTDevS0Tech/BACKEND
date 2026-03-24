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
}
}

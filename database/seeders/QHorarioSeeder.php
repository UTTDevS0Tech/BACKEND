<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QHorarioSeeder extends Seeder
{
    public function run(): void
    {
        $personales = [1, 2];

        $horarios = [];

        foreach ($personales as $personal_id) {
            $horarios = array_merge($horarios, [
                [
                    'dia_semana'  => 'Lunes',
                    'hora_inicio' => '09:00:00',
                    'hora_fin'    => '18:00:00',
                    'activo'      => true,
                    'personal_id' => $personal_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'dia_semana'  => 'Martes',
                    'hora_inicio' => '09:00:00',
                    'hora_fin'    => '18:00:00',
                    'activo'      => true,
                    'personal_id' => $personal_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'dia_semana'  => 'Miércoles',
                    'hora_inicio' => '09:00:00',
                    'hora_fin'    => '18:00:00',
                    'activo'      => true,
                    'personal_id' => $personal_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'dia_semana'  => 'Jueves',
                    'hora_inicio' => '09:00:00',
                    'hora_fin'    => '18:00:00',
                    'activo'      => true,
                    'personal_id' => $personal_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'dia_semana'  => 'Viernes',
                    'hora_inicio' => '09:00:00',
                    'hora_fin'    => '18:00:00',
                    'activo'      => true,
                    'personal_id' => $personal_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'dia_semana'  => 'Sábado',
                    'hora_inicio' => '10:00:00',
                    'hora_fin'    => '14:00:00',
                    'activo'      => true,
                    'personal_id' => $personal_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'dia_semana'  => 'Domingo',
                    'hora_inicio' => '00:00:00',
                    'hora_fin'    => '00:00:00',
                    'activo'      => false,
                    'personal_id' => $personal_id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }

        DB::table('horarios')->insert($horarios);
    }
}
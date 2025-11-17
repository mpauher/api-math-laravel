<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnosSeeder extends Seeder
{
    public function run()
    {
        $turnos = [
            [
                'nombre' => 'Mañana',
                'hora_inicio' => '06:00:00',
                'hora_fin' => '14:00:00'
            ],
            [
                'nombre' => 'Tarde',
                'hora_inicio' => '14:00:00',
                'hora_fin' => '22:00:00'
            ]
        ];

        foreach ($turnos as $turno) {
            DB::table('turnos')->updateOrInsert(
                ['nombre' => $turno['nombre']], // evita duplicados
                $turno
            );
        }
    }
}

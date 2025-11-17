<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargosSeeder extends Seeder
{
    public function run()
    {
        $cargos = ['Supervisor', 'Operario'];

        foreach ($cargos as $cargo) {
            DB::table('cargos')->updateOrInsert(
                ['nombre' => $cargo]
            );
        }
    }
}

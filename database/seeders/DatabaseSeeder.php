<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

// Importa tus seeders
use Database\Seeders\CargosSeeder;
use Database\Seeders\TurnosSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed del usuario de prueba
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Llamar tus seeders para llenar cargos y turnos
        $this->call([
            CargosSeeder::class,
            TurnosSeeder::class,
        ]);
    }
}

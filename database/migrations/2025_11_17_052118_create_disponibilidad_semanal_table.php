<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disponibilidad_semanal', function (Blueprint $table) {
            $table->id('id_disponibilidad');
            $table->foreignId('id_trabajador')->constrained('trabajadores','id_trabajador');
            $table->foreignId('id_turno')->constrained('turnos','id_turno');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilidad_semanal');
    }
};

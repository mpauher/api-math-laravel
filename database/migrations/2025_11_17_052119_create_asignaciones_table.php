<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->foreignId('id_trabajador')->constrained('trabajadores','id_trabajador');
            $table->foreignId('id_turno')->constrained('turnos','id_turno');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trabajadores', function (Blueprint $table) {
            $table->id('id_trabajador');
            $table->string('nombre', 100);
            $table->foreignId('id_cargo')->constrained('cargos','id_cargo');
            $table->boolean('no_sabados_domingos')->default(false);
            $table->boolean('max_5_turnos')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};

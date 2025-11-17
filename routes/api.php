<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\DisponibilidadSemanalController;
use App\Http\Controllers\AsignacionController;

// Ejemplo de prueba
Route::get('/hello', function () {
    return response()->json(['message' => 'paujajaajaj']);
});

// Generar semana — POST 
Route::post('/asignaciones/generar', [AsignacionController::class, 'generarSemana']);

// Listar asignaciones — GET
Route::get('/asignaciones', [AsignacionController::class, 'index']);

Route::get('/asignaciones/ultima', [AsignacionController::class, 'mostrarUltimaSemana']);


// Recursos REST
Route::apiResource('cargos', CargoController::class);
Route::apiResource('trabajadores', TrabajadorController::class);
Route::apiResource('turnos', TurnoController::class);
Route::apiResource('disponibilidad', DisponibilidadSemanalController::class);

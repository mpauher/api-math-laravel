<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\DisponibilidadSemanalController;
use App\Http\Controllers\AsignacionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/hello', function (Request $request) {
    return response()->json(['message' => 'paujajaajaj']);
});

Route::apiResource('cargos', CargoController::class);
Route::apiResource('trabajadores', TrabajadorController::class);
Route::apiResource('turnos', TurnoController::class);
Route::apiResource('disponibilidad', DisponibilidadSemanalController::class);
Route::apiResource('asignaciones', AsignacionController::class);
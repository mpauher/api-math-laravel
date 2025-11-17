<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrabajadorController extends Controller
{
    // Listar todos los trabajadores con cargo y turno
    public function index()
    {
        $trabajadores = Trabajador::with('cargo', 'turno')->get();
        return response()->json($trabajadores, 200);
    }

    // Crear un trabajador
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'id_turno' => 'required|exists:turnos,id_turno', // campo de turno
            'no_sabados_domingos' => 'boolean',
            'max_5_turnos' => 'boolean'
        ]);

        // Crear trabajador
        $trabajador = Trabajador::create($request->all());

        // Crear disponibilidad semanal automática (sin día específico)
        DB::table('disponibilidad_semanal')->insert([
            'id_trabajador' => $trabajador->id_trabajador,
            'id_turno' => $trabajador->id_turno,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Trabajador registrado correctamente',
            'trabajador' => $trabajador
        ], 201);
    }

    // Ver un trabajador específico
    public function show($id)
    {
        $trabajador = Trabajador::with('cargo', 'turno', 'disponibilidad_semanal', 'asignaciones')
            ->where('id_trabajador', $id)
            ->first();

        if (!$trabajador) {
            return response()->json(['error' => 'Trabajador no encontrado'], 404);
        }

        return response()->json($trabajador, 200);
    }

    // Actualizar un trabajador
    public function update(Request $request, $id)
    {
        $trabajador = Trabajador::where('id_trabajador', $id)->first();

        if (!$trabajador) {
            return response()->json(['error' => 'Trabajador no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'id_turno' => 'required|exists:turnos,id_turno',
            'no_sabados_domingos' => 'boolean',
            'max_5_turnos' => 'boolean'
        ]);

        $trabajador->update($request->all());

        // Actualizar disponibilidad semanal
        DB::table('disponibilidad_semanal')
            ->where('id_trabajador', $trabajador->id_trabajador)
            ->update(['id_turno' => $trabajador->id_turno, 'updated_at' => now()]);

        return response()->json([
            'message' => 'Trabajador actualizado correctamente',
            'trabajador' => $trabajador
        ], 200);
    }

    // Eliminar un trabajador
    public function destroy($id)
    {
        $trabajador = Trabajador::where('id_trabajador', $id)->first();

        if (!$trabajador) {
            return response()->json(['error' => 'Trabajador no encontrado'], 404);
        }

        // Eliminar también sus disponibilidades semanales
        DB::table('disponibilidad_semanal')->where('id_trabajador', $trabajador->id_trabajador)->delete();

        $trabajador->delete();

        return response()->json(['message' => 'Trabajador eliminado'], 200);
    }
}

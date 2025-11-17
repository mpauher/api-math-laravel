<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = Trabajador::with('cargo')->get();
        return response()->json($trabajadores, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'no_sabados_domingos' => 'boolean',
            'max_5_turnos' => 'boolean'
        ]);

        $trabajador = Trabajador::create($request->all());
        return response()->json($trabajador, 201);
    }

    public function show(Trabajador $trabajador)
    {
        $trabajador->load('cargo', 'disponibilidades', 'asignaciones');
        return response()->json($trabajador, 200);
    }

    public function update(Request $request, $id)
    {
        // Buscar por la clave primaria real
        $trabajador = Trabajador::where('id_trabajador', $id)->first();

        if (!$trabajador) {
            return response()->json(['error' => 'Trabajador no encontrado'], 404);
        }

        // Validación
        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'no_sabados_domingos' => 'boolean',
            'max_5_turnos' => 'boolean'
        ]);

        // Actualizar
        $trabajador->update($request->all());

        return response()->json([
            'message' => 'Trabajador actualizado correctamente',
            'trabajador' => $trabajador
        ], 200);
    }

    public function destroy($id)
    {
        $trabajador = Trabajador::where('id_trabajador', $id)->first();

        if (!$trabajador) {
            return response()->json(['error' => 'Trabajador no encontrado'], 404);
        }

        $trabajador->delete();

        return response()->json(['message' => 'Trabajador eliminado'], 200);
    }

}

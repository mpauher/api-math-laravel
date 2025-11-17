<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function index()
    {
        $asignaciones = Asignacion::with('trabajador', 'turno')->get();
        return response()->json($asignaciones, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'fecha' => 'required|date'
        ]);

        $asignacion = Asignacion::create($request->all());
        return response()->json($asignacion, 201);
    }

    public function show(Asignacion $asignacion)
    {
        $asignacion->load('trabajador', 'turno');
        return response()->json($asignacion, 200);
    }

    public function update(Request $request, Asignacion $asignacion)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'fecha' => 'required|date'
        ]);

        $asignacion->update($request->all());
        return response()->json($asignacion, 200);
    }

    public function destroy(Asignacion $asignacion)
    {
        $asignacion->delete();
        return response()->json(['message' => 'Asignación eliminada'], 200);
    }
}

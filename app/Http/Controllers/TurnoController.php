<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::all();
        return response()->json($turnos, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|in:Mañana,Tarde',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i',
        ]);

        $turno = Turno::create($request->all());
        return response()->json($turno, 201);
    }

    public function show(Turno $turno)
    {
        return response()->json($turno, 200);
    }

    public function update(Request $request, Turno $turno)
    {
        $request->validate([
            'nombre' => 'required|in:Mañana,Tarde',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i',
        ]);

        $turno->update($request->all());
        return response()->json($turno, 200);
    }

    public function destroy(Turno $turno)
    {
        $turno->delete();
        return response()->json(['message' => 'Turno eliminado'], 200);
    }
}

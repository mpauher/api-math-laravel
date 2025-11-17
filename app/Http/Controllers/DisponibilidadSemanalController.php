<?php

namespace App\Http\Controllers;

use App\Models\DisponibilidadSemanal;
use Illuminate\Http\Request;

class DisponibilidadSemanalController extends Controller
{
    public function index()
    {
        $disponibilidades = DisponibilidadSemanal::with('trabajador', 'turno')->get();
        return response()->json($disponibilidades, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'
        ]);

        $disponibilidad = DisponibilidadSemanal::create($request->all());
        return response()->json($disponibilidad, 201);
    }

    public function show(DisponibilidadSemanal $disponibilidadSemanal)
    {
        $disponibilidadSemanal->load('trabajador', 'turno');
        return response()->json($disponibilidadSemanal, 200);
    }

    public function update(Request $request, DisponibilidadSemanal $disponibilidadSemanal)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'
        ]);

        $disponibilidadSemanal->update($request->all());
        return response()->json($disponibilidadSemanal, 200);
    }

    public function destroy(DisponibilidadSemanal $disponibilidadSemanal)
    {
        $disponibilidadSemanal->delete();
        return response()->json(['message' => 'Disponibilidad eliminada'], 200);
    }
}

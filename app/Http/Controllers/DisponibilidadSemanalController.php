<?php

namespace App\Http\Controllers;

use App\Models\DisponibilidadSemanal;
use App\Models\Trabajador;
use App\Models\Turno;
use Illuminate\Http\Request;

class DisponibilidadSemanalController extends Controller
{
    public function index()
    {
        $disponibilidades = DisponibilidadSemanal::with('trabajador', 'turno')->get();
        return view('disponibilidades.index', compact('disponibilidades'));
    }

    public function create()
    {
        $trabajadores = Trabajador::all();
        $turnos = Turno::all();
        $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
        return view('disponibilidades.create', compact('trabajadores','turnos','dias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'
        ]);

        DisponibilidadSemanal::create($request->all());
        return redirect()->route('disponibilidad_semanal.index')->with('success', 'Disponibilidad registrada.');
    }

    public function show(DisponibilidadSemanal $disponibilidadSemanal)
    {
        $disponibilidadSemanal->load('trabajador', 'turno');
        return view('disponibilidades.show', compact('disponibilidadSemanal'));
    }

    public function edit(DisponibilidadSemanal $disponibilidadSemanal)
    {
        $trabajadores = Trabajador::all();
        $turnos = Turno::all();
        $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
        return view('disponibilidades.edit', compact('disponibilidadSemanal','trabajadores','turnos','dias'));
    }

    public function update(Request $request, DisponibilidadSemanal $disponibilidadSemanal)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'
        ]);

        $disponibilidadSemanal->update($request->all());
        return redirect()->route('disponibilidad_semanal.index')->with('success', 'Disponibilidad actualizada.');
    }

    public function destroy(DisponibilidadSemanal $disponibilidadSemanal)
    {
        $disponibilidadSemanal->delete();
        return redirect()->route('disponibilidad_semanal.index')->with('success', 'Disponibilidad eliminada.');
    }
}

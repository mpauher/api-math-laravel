<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Trabajador;
use App\Models\Turno;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function index()
    {
        $asignaciones = Asignacion::with('trabajador', 'turno')->get();
        return view('asignaciones.index', compact('asignaciones'));
    }

    public function create()
    {
        $trabajadores = Trabajador::all();
        $turnos = Turno::all();
        return view('asignaciones.create', compact('trabajadores','turnos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'fecha' => 'required|date'
        ]);

        Asignacion::create($request->all());
        return redirect()->route('asignaciones.index')->with('success', 'Asignación registrada.');
    }

    public function show(Asignacion $asignacion)
    {
        $asignacion->load('trabajador', 'turno');
        return view('asignaciones.show', compact('asignacion'));
    }

    public function edit(Asignacion $asignacion)
    {
        $trabajadores = Trabajador::all();
        $turnos = Turno::all();
        return view('asignaciones.edit', compact('asignacion','trabajadores','turnos'));
    }

    public function update(Request $request, Asignacion $asignacion)
    {
        $request->validate([
            'id_trabajador' => 'required|exists:trabajadores,id_trabajador',
            'id_turno' => 'required|exists:turnos,id_turno',
            'fecha' => 'required|date'
        ]);

        $asignacion->update($request->all());
        return redirect()->route('asignaciones.index')->with('success', 'Asignación actualizada.');
    }

    public function destroy(Asignacion $asignacion)
    {
        $asignacion->delete();
        return redirect()->route('asignaciones.index')->with('success', 'Asignación eliminada.');
    }
}

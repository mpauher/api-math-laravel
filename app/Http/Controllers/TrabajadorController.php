<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use App\Models\Cargo;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = Trabajador::with('cargo')->get();
        return view('trabajadores.index', compact('trabajadores'));
    }

    public function create()
    {
        $cargos = Cargo::all();
        return view('trabajadores.create', compact('cargos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'no_sabados_domingos' => 'boolean',
            'max_5_turnos' => 'boolean'
        ]);

        Trabajador::create($request->all());
        return redirect()->route('trabajadores.index')->with('success', 'Trabajador creado.');
    }

    public function show(Trabajador $trabajador)
    {
        $trabajador->load('cargo', 'disponibilidades', 'asignaciones');
        return view('trabajadores.show', compact('trabajador'));
    }

    public function edit(Trabajador $trabajador)
    {
        $cargos = Cargo::all();
        return view('trabajadores.edit', compact('trabajador', 'cargos'));
    }

    public function update(Request $request, Trabajador $trabajador)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cargo' => 'required|exists:cargos,id_cargo',
            'no_sabados_domingos' => 'boolean',
            'max_5_turnos' => 'boolean'
        ]);

        $trabajador->update($request->all());
        return redirect()->route('trabajadores.index')->with('success', 'Trabajador actualizado.');
    }

    public function destroy(Trabajador $trabajador)
    {
        $trabajador->delete();
        return redirect()->route('trabajadores.index')->with('success', 'Trabajador eliminado.');
    }
}

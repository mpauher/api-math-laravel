<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::all();
        return response()->json($cargos);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:50']);
        $cargo = Cargo::create($request->all());
        return response()->json($cargo, 201);
    }

    public function show(Cargo $cargo)
    {
        return response()->json($cargo, 200);
    }

    public function update(Request $request, Cargo $cargo)
    {
        $request->validate(['nombre' => 'required|string|max:50']);
        $cargo->update($request->all());
        return response()->json($cargo, 200);
    }

    public function destroy(Cargo $cargo)
    {
        $cargo->delete();
        return response()->json(['message' => 'Cargo eliminado'], 200);
    }
}

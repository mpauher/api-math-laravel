<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignacion;
use App\Models\Trabajador;
use Illuminate\Support\Carbon;

class AsignacionController extends Controller
{
    /**
     * Generar asignaciones para una semana completa (lunes a domingo).
     */
    public function generarSemana(Request $request)
    {
        // Si envían fecha_inicio, usamos esa, si no usamos lunes siguiente
        if ($request->has('fecha_inicio')) {
            $inicio = Carbon::parse($request->fecha_inicio)->startOfWeek(Carbon::MONDAY);
        } else {
            $inicio = Carbon::now()->next(Carbon::MONDAY);
        }

        $resultados = [];

        for ($i = 0; $i < 7; $i++) {
            $fecha = $inicio->copy()->addDays($i);

            try {
                $asignacionDia = $this->generarAsignacionDia($fecha);
                $resultados[$fecha->toDateString()] = $asignacionDia;
            } catch (\Exception $e) {
                return response()->json([
                    'error' => "Error en {$fecha->toDateString()}: " . $e->getMessage()
                ], 400);
            }
        }

        return response()->json([
            'message' => 'Asignaciones semanales generadas correctamente',
            'asignaciones' => $resultados
        ], 201);
    }

    /**
     * Genera las asignaciones de un día específico.
     */
    private function generarAsignacionDia($fecha)
    {
        $trabajadores = Trabajador::all();

        if ($trabajadores->count() == 0) {
            throw new \Exception('No hay trabajadores registrados');
        }

        // Filtrar restricciones de no trabajar fines de semana
        $trabajadoresValidos = $trabajadores->filter(function ($t) use ($fecha) {
            if ($t->no_sabados_domingos && ($fecha->isSaturday() || $fecha->isSunday())) {
                return false;
            }
            return true;
        })->values();

        $supervisores = $trabajadoresValidos->where('id_cargo', 1)->values();
        $operarios    = $trabajadoresValidos->where('id_cargo', 2)->values();

        if ($supervisores->count() < 1) {
            throw new \Exception('No hay suficientes supervisores disponibles');
        }
        if ($operarios->count() < 2) {
            throw new \Exception('No hay suficientes operarios disponibles');
        }

        // IDs de trabajadores seleccionados para no repetir si max_5_turnos = 1
        $idsSeleccionados = [];

        // ---- TURNO MAÑANA ----
        $supManana = $this->seleccionarTrabajador($supervisores, $idsSeleccionados);
        $idsSeleccionados[] = $supManana->id_trabajador;

        $opsManana = $this->seleccionarTrabajadores($operarios, 2, $idsSeleccionados);
        foreach ($opsManana as $op) {
            if ($op->max_5_turnos == 1) {
                $idsSeleccionados[] = $op->id_trabajador;
            }
        }

        // ---- TURNO TARDE ----
        $supTarde = $this->seleccionarTrabajador($supervisores, $idsSeleccionados);
        if ($supTarde->max_5_turnos == 1) {
            $idsSeleccionados[] = $supTarde->id_trabajador;
        }

        $opsTarde = $this->seleccionarTrabajadores($operarios, 2, $idsSeleccionados);
        foreach ($opsTarde as $op) {
            if ($op->max_5_turnos == 1) {
                $idsSeleccionados[] = $op->id_trabajador;
            }
        }

        // ---- GUARDAR EN BD ----
        $this->guardarAsignaciones($fecha, 1, array_merge([$supManana], $opsManana));
        $this->guardarAsignaciones($fecha, 2, array_merge([$supTarde], $opsTarde));

        // ---- RETORNAR FORMATO PARA CALENDAR ----
        return [
            'mañana' => [
                $supManana->nombre,
                $opsManana[0]->nombre,
                $opsManana[1]->nombre
            ],
            'tarde' => [
                $supTarde->nombre,
                $opsTarde[0]->nombre,
                $opsTarde[1]->nombre
            ]
        ];
    }

    /**
     * Selecciona un trabajador considerando restricciones de max_5_turnos
     */
    private function seleccionarTrabajador($trabajadores, $idsExcluidos = [])
    {
        $disponibles = $trabajadores->filter(function ($t) use ($idsExcluidos) {
            if ($t->max_5_turnos == 1 && in_array($t->id_trabajador, $idsExcluidos)) {
                return false;
            }
            return true;
        })->values();

        if ($disponibles->isEmpty()) {
            $disponibles = $trabajadores;
        }

        return $disponibles->random();
    }

    /**
     * Selecciona varios trabajadores considerando restricciones de max_5_turnos
     */
    private function seleccionarTrabajadores($trabajadores, $cantidad, $idsExcluidos = [])
    {
        $disponibles = $trabajadores->filter(function ($t) use ($idsExcluidos) {
            if ($t->max_5_turnos == 1 && in_array($t->id_trabajador, $idsExcluidos)) {
                return false;
            }
            return true;
        })->values();

        if ($disponibles->count() < $cantidad) {
            $faltan = $cantidad - $disponibles->count();
            $repetibles = $trabajadores->filter(function ($t) use ($idsExcluidos) {
                return $t->max_5_turnos == 0 || !in_array($t->id_trabajador, $idsExcluidos);
            })->values();

            $disponibles = $disponibles->merge($repetibles);
        }

        return $disponibles->random($cantidad)->all();
    }

    /**
     * Guardar varias asignaciones en BD.
     */
    private function guardarAsignaciones($fecha, $turnoId, $trabajadores)
    {
        foreach ($trabajadores as $t) {
            Asignacion::create([
                'fecha' => $fecha->format('Y-m-d'),
                'id_turno' => $turnoId,
                'id_trabajador' => $t->id_trabajador
            ]);
        }
    }

    /**
     * Listar todas las asignaciones
     */
    public function index()
    {
        return Asignacion::with('trabajador.cargo')->get();
    }

    /**
     * Mostrar la última semana completa de asignaciones
     * Solo 1 supervisor + 2 operarios por turno, 14 turnos = 42 personas máximo
     */
public function mostrarUltimaSemana()
{
    // Obtener la última fecha registrada
    $ultimaFecha = Asignacion::max('fecha');

    if (!$ultimaFecha) {
        return response()->json([], 200); // No hay asignaciones
    }

    $fechaCarbon = \Carbon\Carbon::parse($ultimaFecha);
    $inicioSemana = $fechaCarbon->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $finSemana    = $fechaCarbon->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

    $result = [];

    // Recorremos día por día para asegurarnos de respetar el orden
    for ($fecha = $inicioSemana->copy(); $fecha->lte($finSemana); $fecha->addDay()) {
        $fechaStr = $fecha->format('Y-m-d');

        // Para turno mañana
        $manana = Asignacion::with('trabajador')
            ->where('fecha', $fechaStr)
            ->where('id_turno', 1)
            ->orderBy('id_asignacion', 'desc') // Tomamos los últimos registros
            ->take(3)
            ->get()
            ->pluck('trabajador.nombre')
            ->toArray();

        // Para turno tarde
        $tarde = Asignacion::with('trabajador')
            ->where('fecha', $fechaStr)
            ->where('id_turno', 2)
            ->orderBy('id_asignacion', 'desc')
            ->take(3)
            ->get()
            ->pluck('trabajador.nombre')
            ->toArray();

        $result[$fechaStr] = [
            'mañana' => $manana,
            'tarde'  => $tarde
        ];
    }

    return response()->json($result);
}


}
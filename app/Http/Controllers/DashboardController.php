<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Marcacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Obtenemos el DUI desde el cuerpo de la solicitud POST
        $dui = $request->input('dui');
        $empleado = Empleado::where('dui', $dui)->first();

        // Obtener todos los empleados con sus marcaciones
        $empleados = Empleado::with(['marcaciones' => function($query) {
            $query->select('id', 'hora_entrada', 'entrada_tardia', 'salida_temprana','id_marcador', 'fecha', 'excepciones');
        }])->get();

        // Obtener todos los distritos registrados
        $distritos = Empleado::distinct()->pluck('distrito'); // Obtener distritos únicos

        // Calcular el total de excepciones para cada empleado
        $empleadosConExcepciones = $empleados->map(function($empleado) {
            // Contar cuántas marcaciones tienen excepciones
            $empleado->total_excepciones = $empleado->marcaciones->filter(function($marcacion) {
                return !empty($marcacion->excepciones);
            })->count();

            return $empleado;
        })
        ->filter(function($empleado) {
            return $empleado->total_excepciones > 0; // Filtrar solo los que tienen excepciones
        })
        ->sortByDesc('total_excepciones') // Ordenar de mayor a menor según las excepciones
        ->take(10); // Tomar solo los primeros 5 empleados con más excepciones


        // Calcular el total de entrada tardía para cada empleado
        $empleadoConMasTardias = $empleados->map(function($empleado) {
            $empleado->total_entrada_tardia = $empleado->marcaciones->sum(function($marcacion) {
                return strtotime($marcacion->entrada_tardia) > 0 ? strtotime($marcacion->entrada_tardia) : 0;
            });
            return $empleado;
        })
        ->filter(function($empleado) {
            return $empleado->total_entrada_tardia > 0;
        })
        ->sortByDesc('total_entrada_tardia') // Ordenar de mayor a menor
        ->first(); // Obtener el empleado con más horas de entrada tardía


        
        // Calcular el total de salida temprana para cada empleado
        $empleadoConMasSalidasTempranas = $empleados->map(function($empleado) {
            $empleado->total_salida_temprana = $empleado->marcaciones->sum(function($marcacion) {
                return strtotime($marcacion->salida_temprana) > 0 ? strtotime($marcacion->salida_temprana) : 0;
            });
            return $empleado;
        })
        ->filter(function($empleado) {
            return $empleado->total_salida_temprana > 0;
        })
        ->sortByDesc('total_salida_temprana') // Ordenar de mayor a menor
        ->first(); 

        // Calcular las excepciones más repetidas
        $excepcionesMasRepetidas = Marcacion::select('excepciones', DB::raw('count(*) as cantidad'))
            ->whereNotNull('excepciones') // Filtra solo las marcaciones que tengan excepciones
            ->groupBy('excepciones') // Agrupar por el nombre de la excepción
            ->orderByDesc('cantidad') // Ordenar de mayor a menor cantidad
            ->take(5) // Tomar las 5 más repetidas
            ->get();


        

        // Preparar los datos para el gráfico (Nombre del empleado y número de excepciones)
        $empleadosParaGrafico = $empleadosConExcepciones->map(function($empleado) {
            return [
                'nombre' => $empleado->nombre,
                'excepciones' => $empleado->total_excepciones,
            ];
        });

        // Cantidad de empleados por distrito
        $empleadosPorDistrito = DB::table('empleados')
        ->select('distrito', DB::raw('COUNT(*) as cantidad'))
        ->groupBy('distrito')
        ->get();



        // Convertir los datos a un formato adecuado para JavaScript
        $nombres = $empleadosParaGrafico->pluck('nombre');
        $excepciones = $empleadosParaGrafico->pluck('excepciones');

        // Obtener el total de empleados activos
        $totalEmpleados = Empleado::where('estado', 1)->count();

        // Obtener el total de marcaciones registradas
        $totalMarcaciones = Marcacion::count();

        // Pasar las variables a la vista
        return view('panel.index', compact('empleadosConExcepciones', 'totalEmpleados', 'totalMarcaciones', 'nombres', 'excepciones', 'empleadoConMasTardias','empleadoConMasSalidasTempranas','excepcionesMasRepetidas','empleadosPorDistrito','distritos'));
    }
    
    public function obtenerDatos(Request $request) {
        $distrito = $request->input('distrito');
        
        $query = DB::table('empleados')
            ->join('marcaciones', 'empleados.id', '=', 'marcaciones.id_marcador')
            ->select('empleados.nombre', DB::raw('COUNT(marcaciones.excepciones) as total_excepciones'))
            ->when($distrito, function($query) use ($distrito) {
                return $query->where('empleados.distrito', $distrito);
            })
            ->groupBy('empleados.nombre')
            ->orderByDesc('total_excepciones')
            ->limit(10)
            ->get();
    
        $nombres = $query->pluck('nombre');
        $excepciones = $query->pluck('total_excepciones');
    
        $excepcionesMasRepetidas = DB::table('marcaciones')
            ->select('excepciones', DB::raw('COUNT(*) as cantidad'))
            ->when($distrito, function($query) use ($distrito) {
                return $query->join('empleados', 'marcaciones.id_marcador', '=', 'empleados.id')
                            ->where('empleados.distrito', $distrito);
            })
            ->whereNotNull('excepciones') // 🔥 Agregamos este filtro para excluir los NULL
            ->groupBy('excepciones')
            ->orderByDesc('cantidad')
            ->limit(5)
            ->get();


    
        return response()->json([
            'nombres' => $nombres,
            'excepciones' => $excepciones,
            'excepcionesMasRepetidas' => [
                'labels' => $excepcionesMasRepetidas->pluck('excepciones'),
                'valores' => $excepcionesMasRepetidas->pluck('cantidad')
            ]
        ]);
    }
    
    public function filtrarDistrito(Request $request)
    {
        $distrito = $request->input('distrito');
    
        $queryEmpleados = DB::table('marcaciones')
            ->join('empleados', 'marcaciones.id_marcador', '=', 'empleados.id_marcador')
            ->select('empleados.nombre', DB::raw('COUNT(marcaciones.excepciones) as total_excepciones'))
            ->groupBy('empleados.nombre')
            ->orderByDesc('total_excepciones')
            ->limit(10);
    
        $queryExcepciones = DB::table('marcaciones')
            ->join('empleados', 'marcaciones.id_marcador', '=', 'empleados.id_marcador')
            ->select('marcaciones.excepciones', DB::raw('COUNT(*) as cantidad'))
            ->whereNotNull('marcaciones.excepciones') // 🔥 Aquí agregamos el filtro
            ->groupBy('marcaciones.excepciones')
            ->orderByDesc('cantidad')
            ->limit(5);

    
        // Si se selecciona un distrito, filtrar los datos
        if (!empty($distrito)) {
            $queryEmpleados->where('empleados.distrito', $distrito);
            $queryExcepciones->where('empleados.distrito', $distrito);
        }
    
        return response()->json([
            'nombres' => $queryEmpleados->pluck('nombre'),
            'excepciones' => $queryEmpleados->pluck('total_excepciones'),
            'excepcionesLabels' => $queryExcepciones->pluck('excepciones'),
            'excepcionesData' => $queryExcepciones->pluck('cantidad')
        ]);
    }


}



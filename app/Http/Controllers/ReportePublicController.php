<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Marcacion;  // Importar el modelo Marcacion
use Carbon\Carbon;  // Importar Carbon para trabajar con fechas

class ReportePublicController extends Controller
{
    public function index()
    {
        return view('public.reporte_con');
    }

    public function generar(Request $request)
    {
        $dui = $request->input('dui');
        $id_marcador = $request->input('id_marcador');

        // Buscar el empleado por DUI
        $empleado = Empleado::where('dui', $dui)->first();

        if (!$empleado || $empleado->estado == 0) {
            return redirect()->route('consulta')->with('error', 'No se encontro empleado con ese numero de DUI.');
        }

        // Validar que el id_marcador ingresado coincida con el empleado encontrado
        if ($empleado->id_marcador != $id_marcador) {
            return redirect()->route('consulta')->with('error', 'El Codigo de marcador no coincide con el empleado ingresado.');
        }

        // Obtener la fecha del mes anterior
        $fechaAnterior = Carbon::now()->subMonth();
        $nombreMes = ucfirst($fechaAnterior->translatedFormat('F')); // Mes en español

        // Obtener las marcaciones del mes anterior
        $marcaciones = Marcacion::where('id_marcador', $id_marcador)
            ->whereMonth('fecha', $fechaAnterior->month)
            ->whereYear('fecha', $fechaAnterior->year)
            ->select('id', 'fecha', 'hora_entrada', 'hora_salida', 'entrada_tardia', 'salida_temprana', 'excepciones')
            ->get();

        // Calcular totales
        $totalEntradaTardiaSegundos = 0;
        $totalSalidaTempranaSegundos = 0;

        foreach ($marcaciones as $marcacion) {
            if (empty($marcacion->excepciones) && !empty($marcacion->entrada_tardia)) {
                $entradaTardia = Carbon::parse($marcacion->entrada_tardia);
                $totalEntradaTardiaSegundos += $entradaTardia->hour * 3600 + $entradaTardia->minute * 60 + $entradaTardia->second;
            }

            if (empty($marcacion->excepciones) && !empty($marcacion->salida_temprana)) {
                $salidaTemprana = Carbon::parse($marcacion->salida_temprana);
                $totalSalidaTempranaSegundos += $salidaTemprana->hour * 3600 + $salidaTemprana->minute * 60 + $salidaTemprana->second;
            }
        }

        $totalEntradaTardia = gmdate("H:i:s", $totalEntradaTardiaSegundos);
        $totalSalidaTemprana = gmdate("H:i:s", $totalSalidaTempranaSegundos);

        $totalDescuentoSegundos = $totalEntradaTardiaSegundos + $totalSalidaTempranaSegundos;
        $totalDescuentoMes = ($totalDescuentoSegundos > 3600) ? gmdate("H:i:s", $totalDescuentoSegundos - 3600) : "00:00:00";

        return view('public.reporte_consulta', compact('empleado', 'marcaciones', 'totalEntradaTardia', 'totalSalidaTemprana', 'totalDescuentoMes', 'nombreMes'));
    }

    public function validarDUI(Request $request)
    {
        $dui = $request->input('dui');
        $empleado = Empleado::where('dui', $dui)->first();

        return response()->json([
            'existe' => $empleado ? true : false
        ]);
    }
}
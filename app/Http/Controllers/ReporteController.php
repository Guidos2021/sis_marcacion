<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Marcacion;  // Importar el modelo Marcacion
use Carbon\Carbon;  // Importar Carbon para trabajar con fechas

class ReporteController extends Controller
{
    public function index()
    {
        return view('pages.reportes');
    }

    public function generar(Request $request)
    {
        $dui = $request->input('dui');
        $empleado = Empleado::where('dui', $dui)->first();

        if (!$empleado) {
            return redirect()->route('reportes')->with('error', 'No se encontró un empleado con ese DUI.');
        }

        $fechaAnterior = Carbon::now()->subMonth();
        $marcaciones = $empleado->marcaciones()
            ->whereMonth('fecha', $fechaAnterior->month)
            ->whereYear('fecha', $fechaAnterior->year)
            ->select('id', 'fecha', 'hora_entrada', 'hora_salida', 'entrada_tardia', 'salida_temprana', 'excepciones')
            ->get();

        // Inicializar las variables para los totales
        $totalEntradaTardiaSegundos = 0;
        $totalSalidaTempranaSegundos = 0;

        foreach ($marcaciones as $marcacion) {
            // Sumar entrada tardía si no tiene excepciones
            if (empty($marcacion->excepciones) && !empty($marcacion->entrada_tardia)) {
                $entradaTardia = Carbon::parse($marcacion->entrada_tardia);
                $totalEntradaTardiaSegundos += $entradaTardia->hour * 3600 + $entradaTardia->minute * 60 + $entradaTardia->second;
            }

            // Sumar salida temprana si no tiene excepciones
            if (empty($marcacion->excepciones) && !empty($marcacion->salida_temprana)) {
                $salidaTemprana = Carbon::parse($marcacion->salida_temprana);
                $totalSalidaTempranaSegundos += $salidaTemprana->hour * 3600 + $salidaTemprana->minute * 60 + $salidaTemprana->second;
            }
        }

        // Convertir los totales de segundos a formato H:i:s
        $totalEntradaTardia = gmdate("H:i:s", $totalEntradaTardiaSegundos);
        $totalSalidaTemprana = gmdate("H:i:s", $totalSalidaTempranaSegundos);

        // Calcular el total de descuento en el mes (solo el tiempo que supere 1 hora)
        $totalDescuentoSegundos = $totalEntradaTardiaSegundos + $totalSalidaTempranaSegundos;

        if ($totalDescuentoSegundos > 3600) {
            $totalDescuentoMes = gmdate("H:i:s", $totalDescuentoSegundos - 3600);
        } else {
            $totalDescuentoMes = "00:00:00";
        }

        return view('pages.reporte_resultado', compact('empleado', 'marcaciones', 'totalEntradaTardia', 'totalSalidaTemprana', 'totalDescuentoMes'));
    }

}

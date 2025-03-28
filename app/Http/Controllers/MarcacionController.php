<?php

// app/Http/Controllers/MarcacionController.php

namespace App\Http\Controllers;

use App\Models\Marcacion;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Imports\MarcacionesImport; 
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;


class MarcacionController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('marcaciones')->get(); // Cargar empleados con sus marcaciones
        return view('pages.marcaciones', compact('empleados')); // Enviar datos a la vista
    }

    public function store(Request $request)
    {
        // Convertir la fecha al formato correcto antes de la validación
        $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d');

        // Validar los datos antes de insertar en la base de datos
        $request->validate([
            'id_marcador' => 'required|exists:empleados,id_marcador',
            'fecha' => 'required|date_format:d/m/Y',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            'entrada_tardia' => 'required|date_format:H:i',
            'salida_temprana' => 'required|date_format:H:i',
            'excepciones' => 'nullable|string',
        ]);

        // Asegurarse de que las horas tengan formato HH:mm:ss
        $horaEntrada = $request->hora_entrada ? Carbon::createFromFormat('H:i', $request->hora_entrada)->format('H:i:s') : '00:00:00';
        $horaSalida = $request->hora_salida ? Carbon::createFromFormat('H:i', $request->hora_salida)->format('H:i:s') : '00:00:00';
        $entradaTardia = $request->entrada_tardia ? Carbon::createFromFormat('H:i', $request->entrada_tardia)->format('H:i:s') : '00:00:00';
        $salidaTemprana = $request->salida_temprana ? Carbon::createFromFormat('H:i', $request->salida_temprana)->format('H:i:s') : '00:00:00';

        // Guardar la marcación en la base de datos
        Marcacion::create([
            'id_marcador' => $request->id_marcador,
            'fecha' => $fecha, // Aquí se guarda en formato MySQL (Y-m-d)
            'hora_entrada' => $horaEntrada,
            'hora_salida' => $horaSalida,
            'entrada_tardia' => $entradaTardia,
            'salida_temprana' => $salidaTemprana,
            'excepciones' => $request->excepciones,
        ]);

        return redirect()->route('marcaciones.index')->with('success', 'Marcación registrada correctamente.');
    }


    public function importar(Request $request)
    {
        try {
            // Validar el archivo
            $request->validate([
                'archivo' => 'required|mimes:xls,xlsx,csv,txt'
            ]);

            // Crear instancia de importación
            $import = new MarcacionesImport();
            Excel::import($import, $request->file('archivo'));

            $empleadosNoRegistrados = array_unique($import->getEmpleadosNoEncontrados());

            if (!empty($empleadosNoRegistrados)) {
                $mensajeError = "Faltan empleados por registrar: " . implode(", ", $empleadosNoRegistrados);
                return redirect()->back()->with('error', $mensajeError);
            }


            return redirect()->back()->with('success', 'Marcaciones importadas correctamente.');
            
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Error de validación: ' . $e->getMessage());
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $errores = $e->failures();
            $mensajeError = "Exportaciones Parcialmente Correctas: ";
            foreach ($errores as $fallo) {
                $mensajeError .= "Fila " . $fallo->row() . ": " . implode(", ", $fallo->errors()) . ". ";
            }
            return redirect()->back()->with('error', $mensajeError);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado.');
        }
    }

    public function vaciar()
    {
        // Comprobar si hay marcaciones en la tabla
        $marcacionesCount = Marcacion::count();

        if ($marcacionesCount == 0) {
            // Si no hay marcaciones, devolver un mensaje de error
            return redirect()->route('marcaciones.index')->with('error', 'No hay marcaciones para eliminar.');
        }

        // Eliminar todas las marcaciones
        Marcacion::truncate();

        // Redirigir con mensaje de éxito
        return redirect()->route('marcaciones.index')->with('success', 'Todas las marcaciones han sido eliminadas.');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Marcacion;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmpleadosImport; 
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Obtener todos los empleados activos
        $empleados = Empleado::where('estado', 1)->get();
        return view('pages.empleados', ['empleados' => $empleados]);
    }

    public function store(Request $request) {
        // Validar los datos entrantes
        /*$request->validate([
            'dui' => 'required|unique:empleados|regex:/^\d{8}-\d{1}$/',
            'id_marcador' => 'required|integer|unique:empleados',
            'nombre' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'distrito' => 'required|in:Zacatecoluca,San Juan Nonualco,San Rafael Obrajuelo', // Validación del distrito
        ]);*/

        $request->validate([
            'dui' => 'required|unique:empleados|regex:/^\d{8}-\d{1}$/',
            'id_marcador' => 'required|integer|unique:empleados',
            'nombre' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'distrito' => 'required|in:Zacatecoluca,San Juan Nonualco,San Rafael Obrajuelo', // Validación del distrito
        ]);
        
    
        // Crear un nuevo empleado con los campos necesarios
        $empleado = Empleado::create([
            'dui' => $request->dui,
            'id_marcador' => $request->id_marcador,
            'nombre' => $request->nombre,
            'cargo' => $request->cargo,
            'distrito' => $request->distrito,
        ]);

        // Asignar las marcaciones sin ID al nuevo empleado
        Marcacion::whereNull('id_marcador')->update(['id_marcador' => $empleado->id_marcador]);
    
        return redirect()->route('empleados.index')->with('success', 'Empleado agregado correctamente.');
    }

    public function importar(Request $request)
    {
        try {
            $request->validate([
                'archivo' => 'required|mimes:xls,xlsx,csv,txt'
            ]);

            $import = new EmpleadosImport();
            Excel::import($import, $request->file('archivo'));

            // Obtener errores de empleados duplicados
            $errores = $import->getErrores();
            
            if (!empty($errores)) {
                return redirect()->back()->with([
                    'success' => 'Empleados importados correctamente, pero algunos fueron omitidos.',
                    'error' => implode('<br>', $errores) // Mostramos los errores en la vista
                ]);
            }

            return redirect()->back()->with('success', 'Todos los empleados fueron importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado.');
        }
    }



    public function update(Request $request, Empleado $empleado)
    {
        // Validar los datos
        $request->validate([
            'dui' => 'required|max:10|regex:/^\d{8}-\d{1}$/',
            'id_marcador' => 'nullable|integer',
            'nombre' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'distrito' => 'required|in:Zacatecoluca,San Juan Nonualco,San Rafael Obrajuelo',
        ]);

        // Actualizar el empleado
        $empleado->update([
            'dui' => $request->dui,
            'id_marcador' => $request->id_marcador,
            'nombre' => $request->nombre,
            'cargo' => $request->cargo,
            'distrito' => $request->distrito,
        ]);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente');
    }

    public function eliminar(Empleado $empleado)
    {
        $empleado->estado = 0;
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}

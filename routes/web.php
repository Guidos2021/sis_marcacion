<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MarcacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReportePublicController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Ruta para el panel principal
Route::get('/panel', [DashboardController::class, 'index'])->middleware('auth')->name('panel');
// Ruta para obtener las excepciones más repetidas por distrito
Route::get('/dashboard/datos', [DashboardController::class, 'obtenerDatos'])->middleware('auth')->name('dashboard.datos');
Route::get('/filtrar-distrito', [DashboardController::class, 'filtrarDistrito'])->name('dashboard.filtrarDistrito');





/*Route::get('/reportes', function () {
    return view('pages.reportes');
})->middleware('auth')->name('reportes'); // Requiere que el usuario esté autenticado.*/

// Rutas de Marcaciones
Route::get('/marcaciones', [MarcacionController::class, 'index'])->middleware('auth')->name('marcaciones.index');
Route::post('/marcaciones/store', [MarcacionController::class, 'store'])->middleware('auth')->name('marcaciones.store');
Route::post('/marcaciones/importar', [MarcacionController::class, 'importar'])->middleware('auth')->name('marcaciones.importar');
Route::delete('/marcaciones/vaciar', [MarcacionController::class, 'vaciar'])->middleware('auth')->name('marcaciones.vaciar');

// Rutas de Reportes
Route::get('/reportes', [ReporteController::class, 'index'])->middleware('auth')->name('reportes');
Route::match(['get', 'post'],'/reportes/generar', [ReporteController::class, 'generar'])->middleware('auth')->name('reportes.generar');

// Rutas de Reportes publica
Route::get('/consulta', [ReportePublicController::class, 'index'])->name('consulta');
Route::match(['get', 'post'],'/consulta/generar', [ReportePublicController::class, 'generar'])->name('consulta.generar');


// Rutas de empleados
Route::get('/empleados', [EmpleadoController::class, 'index'])->middleware('auth')->name('empleados.index');
Route::resource('empleados', EmpleadoController::class)->middleware('auth');

Route::post('/empleados/store', [EmpleadoController::class, 'store'])->middleware('auth')->name('empleados.store');
Route::post('/empleados/importar', [EmpleadoController::class, 'importar'])->middleware('auth')->name('empleados.importar');

Route::put('/empleados/{empleado}/eliminar', [EmpleadoController::class, 'eliminar'])->middleware('auth')->name('empleados.eliminar');







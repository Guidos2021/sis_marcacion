@extends('dashboard')

@section('title','Panel')

@push('css')
    <style>
        /* Contenedor principal para el contenido */
        .container-fluid {
            padding-top: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Estilo para los gráficos */
        #myChart {
            width: 100%; /* Asegura que el gráfico ocupe todo el ancho disponible */
            max-width: 100%; /* Limita el ancho máximo */
            max-height: 350px;  /* Establece una altura fija */
            margin: 0 auto; /* Centra los gráficos */
        }

        #myPieChart {
            width: 100%;  /* Esto hace que el gráfico ocupe todo el ancho disponible */
            max-width: 600px;  /* Limita el ancho máximo */
            max-height: 387px;  /* Establece una altura fija */
            margin: 0 auto;  /* Centra el gráfico en su contenedor */
        }

        /* Contenedor flexible para los gráficos */
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Opcionales para pruebas de tamaños */
        .chart-container.wide {
            width: 100%; /* O 80% o 90% para reducir el ancho */
        }

        .chart-container.tall {
            height: 400px; /* Ajuste de altura para gráficos */
        }

        .chart-container.custom-width {
            width: 80%; /* Ajusta el ancho al 80% */
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="container mt-3 p-4 rounded shadow-sm bg-light">
        <h2 class="mb-3 text-center" style="font-family: 'Century Gothic', sans-serif; color: #4A90E2; font-weight: bold;">
            📊 Estadísticas de Horarios de Empleados
        </h2>
    
        <div class="d-flex align-items-center justify-content-center gap-3">
            <h5 class="fw-bold text-secondary mb-0">Filtrar por Distrito:</h5>
            <select id="selectDistrito" class="form-select border-primary shadow-sm text-primary fw-bold" 
                    style="min-width: 220px; max-width: 300px; background-color: #f8f9fa; border-radius: 10px;">
                <option value="">Todos</option>
                @foreach ($empleadosPorDistrito as $distrito)
                    <option value="{{ $distrito->distrito }}">{{ $distrito->distrito }}</option>
                @endforeach
            </select>
        </div>
    </div>

    
    <br>
    
    <!-- Fila de Total Empleados y Marcaciones -->
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="card bg-primary text-white h-100 position-relative" style="min-height: 100px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-1">
                    <h4 class="mb-1">Total de Empleados</h4>
                    <p class="fw-bold fs-4 mb-0">{{ $totalEmpleados }}</p>
                </div>
                <i class="fa-solid fa-users position-absolute end-0 bottom-0 me-2 mb-2" 
                   style="font-size: 60px; opacity: 0.3;"></i>
            </div>
        </div>
        


        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="card bg-success text-white h-100 position-relative" style="min-height: 100px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-1">
                    <h4 class="mb-1">Total de Marcaciones</h4>
                    <p class="fw-bold fs-4 mb-0">{{ $totalMarcaciones }}</p>
                </div>
                <i class="fa-solid fa-clock position-absolute end-0 bottom-0 me-2 mb-2" 
                   style="font-size: 60px; opacity: 0.3;"></i>
            </div>
        </div>
    
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="card bg-danger text-white h-100 position-relative" style="min-height: 100px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-1">
                    <h6 class="mb-1">Empleado con Más Entradas Tardías</h6>
                    @if($empleadoConMasTardias)
                        <p class="small mb-0">
                            <strong>
                                <a href="{{ route('reportes.generar', ['dui' => $empleadoConMasTardias->dui]) }}" class="text-white">
                                    {{ $empleadoConMasTardias->nombre }}
                                </a>
                            </strong>
                            <br>
                            <span>{{ gmdate('H:i:s', $empleadoConMasTardias->total_entrada_tardia) }}</span>
                        </p>
                    @else
                        <p class="small mb-0">No disponible</p>
                    @endif
                </div>
                <i class="fa-solid fa-clock-exclamation position-absolute end-0 bottom-0 me-2 mb-2" 
                   style="font-size: 60px; opacity: 0.3;"></i>
            </div>
        </div>
        


        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="card bg-warning text-white h-100 position-relative" style="min-height: 100px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-1">
                    <h6 class="mb-1">Empleado con Más Salidas Tempranas</h6>
                    @if($empleadoConMasSalidasTempranas)
                        <p class="small mb-0">
                            <strong>
                                <a href="{{ route('reportes.generar', ['dui' => $empleadoConMasSalidasTempranas->dui]) }}" class="text-white">
                                    {{ $empleadoConMasSalidasTempranas->nombre }}
                                </a>
                            </strong>  
                            <br>                               
                            <span>{{ gmdate('H:i:s', $empleadoConMasSalidasTempranas->total_salida_temprana) }}</span>
                        </p>
                    @else
                        <p class="small mb-0">No disponible</p>
                    @endif
                </div>
                <i class="fa-solid fa-person-running position-absolute end-0 bottom-0 me-2 mb-2" 
                   style="font-size: 60px; opacity: 0.3;"></i>
            </div>
        </div>        
    </div>
    
    <br>

    <!-- Gráficos -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-10 col-12">
            <div class="card">
                <div class="card-header bg-danger text-white text-center">⚠️ Empleados con Más Excepciones</div>
                <div class="card-body chart-container wide tall">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-10 col-12">
            <div class="card">
                <div class="card-header bg-warning text-white text-center">⚠️ Excepciones Más Repetidas</div>
                <div class="card-body chart-container custom-width">
                    <canvas id="myPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <br>
    
    <div class="row justify-content-center mt-4">
        <div class="col-md-10 col-12">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold text-center"
                     style="font-size: 18px; letter-spacing: 1px;">
                    📍 Empleados por Distrito
                </div>
                <div class="card-body p-4 bg-light rounded-bottom">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Distrito</th>
                                    <th>Número de Empleados</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empleadosPorDistrito as $distrito)
                                    <tr>
                                        <td>{{ $distrito->distrito }}</td>
                                        <td>{{ $distrito->cantidad }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js2')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        let barChart, pieChart;

        function actualizarGraficos(distrito) {
            $.ajax({
                url: "{{ route('dashboard.filtrarDistrito') }}",
                type: "GET",
                data: { distrito: distrito },
                success: function(response) {
                    // Actualizar gráfico de barras
                    if (barChart) {
                        barChart.destroy();
                    }
                    let ctx = document.getElementById('myChart').getContext('2d');
                    barChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.nombres,
                            datasets: [{
                                label: 'Excepciones',
                                data: response.excepciones,
                                backgroundColor: '#ff6666',
                                borderColor: '#ff0000',
                                borderWidth: 1
                            }]
                        }
                    });

                    // Actualizar gráfico de pastel
                    if (pieChart) {
                        pieChart.destroy();
                    }
                    let ctxPie = document.getElementById('myPieChart').getContext('2d');
                    pieChart = new Chart(ctxPie, {
                        type: 'pie',
                        data: {
                            labels: response.excepcionesLabels,
                            datasets: [{
                                label: 'Cantidad de Excepciones',
                                data: response.excepcionesData,
                                backgroundColor: ['#ff6666', '#ffcc66', '#66ff66', '#66ccff', '#ff66ff']
                            }]
                        }
                    });
                }
            });
        }

        // Detectar cambio en el select
        $('#selectDistrito').change(function() {
            let distritoSeleccionado = $(this).val();
            actualizarGraficos(distritoSeleccionado);
        });

        // Cargar gráficos con todos los datos al inicio
        actualizarGraficos('');
    });
</script>

@endpush

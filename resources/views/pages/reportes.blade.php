@extends('dashboard')

@section('title', 'Consulta Historial de Asistencia')

@push('css')
<style>
    /* Fondo blanco y ajuste de todo el contenido */
    body {
        background-color: #fff; /* Fondo blanco para toda la página */
        margin: 0;
        font-family: Arial, sans-serif;
    }

    /* Contenedor principal centrado */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 85vh; /* Centrado vertical completo */
        padding-top: 25px;
        padding-bottom: 30px;
    } 

    /* Caja blanca con sombra */
    .report-box {
        background: white;
        padding: 30px;
        border-radius: 60px;
        box-shadow: 0 10px 100px rgba(0, 0, 0, 0.8);
        text-align: center;
        max-width: 400px;
        width: 100%;
    }

    /* Estilo del logo */
    .logo-container img {
        height: auto;
        max-width: 200px;
        margin-bottom: 20px;
    }

    /* Título del formulario */
    .report-box h2 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 20px;
    }

    /* Botón atractivo */
    .btn-primary {
            background: #007bff;
            border: none;
            width: 80%;
            padding: 12px;
            font-size: 25px;
            border-radius: 30px;
            transition: 0.3s;
            margin-top: 5px;
            border: 2px solid #063052;
            color: rgb(255, 255, 255);
        }

    .btn-primary:hover {
        background: #0056b3;
    }

    /* Input con diseño mejorado */
    .form-control {
        text-align: center;
        font-size: 25px;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }

    /* Mejoras en la responsividad */
    @media (max-width: 767px) {
        .report-box {
            padding: 20px;
        }

        .logo-container img {
            max-width: 180px;
        }

        .report-box h2 {
            font-size: 1.6rem;
        }

        .btn-primary {
            font-size: 16px;
            padding: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="report-box">
        <div class="logo-container">
            <img src="{{ asset('img/imgLPE.webp') }}" alt="Logo">
        </div>
        <h2 class="mb-4">Consulta de Asistencia</h2>
        
        <form action="{{ route('reportes.generar') }}" method="POST">
            @csrf
            <div class="mb-3">
                <h5 for="dui" class="form-label">Ingresa tu numero de DUI</h5>
                <input type="text" class="form-control" id="dui" name="dui" required maxlength="10" oninput="formatearDUI(this)" onkeypress="return soloNumeros(event)">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-file-lines"></i> Generar Reporte</button>
        </form>       

        <!-- Mostrar el mensaje de error aquí -->
        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('js1')
<script>
    // Función que permite solo números en el campo de texto
    function soloNumeros(event) {
        let charCode = event.which ? event.which : event.keyCode;
        return (charCode >= 48 && charCode <= 57); // Solo permite los números del 0 al 9
    }

    // Función para formatear el DUI como 00000000-0
    function formatearDUI(input) {
        let valor = input.value.replace(/\D/g, ''); // Eliminar todo lo que no sea número
        if (valor.length > 8) {
            valor = valor.substring(0, 8) + '-' + valor.substring(8, 9); // Añadir el guion después del octavo dígito
        }
        input.value = valor;
    }
</script>

@endpush

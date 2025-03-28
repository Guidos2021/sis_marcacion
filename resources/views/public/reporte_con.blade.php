@extends('layout')

@section('title', 'Consulta Historial de Asistencia')

@push('css')
<!-- Agregar Bootstrap CSS en el head -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Fondo blanco */
.container-fluid {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: white; /* Fondo completamente blanco */
    padding: 35px;
}

/* Caja blanca con sombra */
.report-box {
    background: white;
    padding: 30px;
    border-radius: 60px;
    box-shadow: 0 10px 100px rgba(0, 0, 0, 0.8);
    text-align: center;
    max-width: 375px;
    width: 100%;
}

/* Asegurar que el logo esté centrado */
.logo-container {
    display: flex;
    justify-content: center;
    margin-bottom: 35px;
}

.logo-container img {
    max-width: 100%;
    height: auto;
}
/* Centrar el contenido dentro del formulario */
.mb-3 {
    text-align: center;
}

/* Centrar el texto de la etiqueta */
.form-label {
    display: block;
    text-align: center;
    font-weight: bold;
}

/* Centrar el input y mejorar el diseño */
.form-control {
    text-align: center;
    font-size: 22px;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 100%;
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

/* Estilos para el fondo del modal */
.modal-content {
    background: #ffffff;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Estilos para la cabecera del modal */
.modal-header {
    background: #007bff;
    color: white;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    padding: 15px;
    text-align: center;
}

/* Estilos para el título del modal */
.modal-title {
    font-size: 22px;
    font-weight: bold;
}

/* Estilo para el botón de cerrar */
.btn-close {
    filter: invert(1);
}

/* Estilo para el cuerpo del modal */
.modal-body {
    text-align: center;
    font-size: 18px;
    padding: 20px;
}

/* Estilos para el input */
.form-control {
    text-align: center;
    font-size: 20px;
    padding: 10px;
    border-radius: 10px;
    border: 2px solid #007bff;
    width: 100%;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Estilo de los botones del modal */
.modal-footer {
    display: flex;
    justify-content: space-between;
    padding: 15px;
}

.btn-cancelar {
    background: #dc3545;
    border: none;
    color: white;
    font-size: 18px;
    padding: 10px 20px;
    border-radius: 10px;
    transition: 0.3s;
}

.btn-cancelar:hover {
    background: #b02a37;
}

.btn-generar {
    background: #007bff;
    border: none;
    color: white;
    font-size: 18px;
    padding: 10px 20px;
    border-radius: 10px;
    transition: 0.3s;
}

.btn-generar:hover {
    background: #0056b3;
}

</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="report-box">
        <div class="logo-container">
            <img src="{{ asset('img/imgLPE.webp') }}" alt="Logo">
        </div>
        <h2 class="mb-4">Consulta de Asistencia</h2>
        
        <form action="{{ route('consulta.generar') }}" method="POST" id="consultaForm"> <!-- Cambiar de GET a POST -->
            @csrf <!-- Asegurarse de incluir el token CSRF -->
            <div class="mb-3">
                <h5 for="dui" class="form-label">Ingresa tu numero de DUI</h5>
                <input type="text" class="form-control" id="dui" name="dui" required maxlength="10" oninput="formatearDUI(this)" onkeypress="return soloNumeros(event)" placeholder="Ej. 00000000-0" onblur="validarFormatoDUI()">
                <div id="dui-error" class="text-danger" style="display: none;">Formato de DUI no válido. Ejemplo: 00000000-0</div>
            </div>
            <button type="button" class="btn btn-primary" id="openModalBtn">
                <i class="fa-solid fa-file-lines"></i> Generar Reporte
            </button>                    
        </form>       
        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal para ingresar ID del marcador -->
<div class="modal fade" id="idMarcadorModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Ingrese su Código de Marcador</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button> -->
            </div>
            <div class="modal-body">
                <form id="modalForm" action="{{ route('consulta.generar') }}" method="POST"> <!-- Cambiar de GET a POST -->
                    @csrf
                    <div class="mb-3">
                        <label for="id_marcador" class="form-label">Código Marcador</label>
                        <input type="text" class="form-control" id="id_marcador" name="id_marcador" required maxlength="10" oninput="Numeros(event)">

                    </div>
                    <input type="hidden" id="duiInput" name="dui">                    
                </form>
                
                <div id="modal-error-message" class="alert alert-danger mt-2" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-generar" onclick="enviarFormulario()">Generar</button>    
                <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal" aria-label="Cerrar" onclick="vaciarDUI()">Cancelar</button>                
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function soloNumeros(event) {
        let charCode = event.which ? event.which : event.keyCode;
        return (charCode >= 48 && charCode <= 57);
    }

    function Numeros(event) {
        let valor = event.target.value;
        // Remueve cualquier carácter que no sea un número
        event.target.value = valor.replace(/\D/g, ''); // \D es una expresión regular que elimina cualquier cosa que no sea un número
    }


    function formatearDUI(input) {
        let valor = input.value.replace(/\D/g, '');
        if (valor.length > 8) {
            valor = valor.substring(0, 8) + '-' + valor.substring(8, 9);
        }
        input.value = valor;
    }

    function validarFormatoDUI() {
        const dui = document.getElementById('dui').value;
        const duiRegex = /^\d{8}-\d{1}$/;
        const errorMessage = document.getElementById('dui-error');

        if (!duiRegex.test(dui)) {
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
        }
    }

    // Función para abrir el modal solo si el DUI es válido
    document.getElementById('openModalBtn').addEventListener('click', function(event) {
        const dui = document.getElementById('dui').value;
        const duiRegex = /^\d{8}-\d{1}$/;
        const errorMessage = document.getElementById('dui-error');

        if (!duiRegex.test(dui)) {
            // Si el DUI no es válido, mostramos el error y no abrimos el modal
            errorMessage.style.display = 'block';
        } else {
            // Si el DUI es válido, ocultamos el error y mostramos el modal
            errorMessage.style.display = 'none';
            var myModal = new bootstrap.Modal(document.getElementById('idMarcadorModal'));
            myModal.show(); // Usamos la nueva API de Bootstrap 5 para mostrar el modal
        }
    });

    function enviarFormulario() {
        let idMarcador = document.getElementById("id_marcador").value.trim();
        let modalErrorMessage = document.getElementById("modal-error-message");

        if (idMarcador === "") {
            modalErrorMessage.textContent = "Por favor ingrese su ID de Marcador.";
            modalErrorMessage.style.display = "block";
            return false;
        }

        modalErrorMessage.style.display = "none";
        let duiValor = document.getElementById("dui").value;
        document.getElementById("duiInput").value = duiValor;

        document.getElementById("modalForm").submit(); // Enviar el formulario por POST
    }

    function vaciarDUI() {
        location.reload(); // Recargar la página para limpiar todo
    }
</script>
@endpush
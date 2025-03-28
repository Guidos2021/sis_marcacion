<!doctype html>
<html lang="en">
    <head>
        <title>Sistema Marcacion - LOGIN</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="{{ asset('assets/estilos.css')}}">
    </head>

    <body>
        <section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="{{ asset('img/imgLPE.webp')}}"
            class="img-fluid" alt="Sample image">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <form method="POST" action="{{ route('login') }}">
            @csrf
                        
            <div class="divider d-flex align-items-center my-4">
                <p class="text-center fw-bold mx-3 mb-0">INICIAR SESION</p>
            </div>

            <!-- DUI input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="nombre">Usuario</label>
                <input type="text" name="nombre" id="nombre" class="form-control form-control-lg" placeholder="Username" required>
                @if ($errors->has('nombre'))
                    <div class="error text-danger">
                        <p>{{ $errors->first('nombre') }}</p>
                    </div>
                @endif
            </div>

            <!-- Password input -->
            <div class="form-outline mb-3">
                <label class="form-label" for="password">CONTRASEÑA</label>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" required>                
                @if ($errors->has('password'))
                    <div class="error text-danger">
                        <p>{{ $errors->first('password') }}</p>
                    </div>
                @endif
            </div>

            <div class="text-center text-lg-start mt-4 pt-2">
                <button type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Iniciar sesión</button>                
            </div>
            </form>
        </div>
        </div>
    </div>
    <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
        <div class="text-white mb-3 mb-md-0">
        Alcaldia La Paz Este - 2025
        </div>
    
    </div>
    </section>


        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <script>
            document.getElementById('dui').addEventListener('input', function (e) {
                let input = e.target.value;
                
                // Eliminar cualquier carácter no numérico
                input = input.replace(/\D/g, '');
                
                // Formatear el DUI en el formato 00000000-0
                if (input.length > 8) {
                    input = input.slice(0, 8) + '-' + input.slice(8, 9); // Añadir guion después de 8 caracteres
                }
                
                // Asignar el valor formateado al input
                e.target.value = input;
            });
        </script>

        <script>
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33'
                });
            @endif
        </script>

    </body>
</html>

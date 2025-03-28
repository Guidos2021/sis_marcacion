<!DOCTYPE html>
<html lang="{{ str_replace('_', '_', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="La Paz Este" />
        <meta name="author" content="La Paz Este" />
        <title>Sistema Marcacion - @yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="{{ asset('assets/style.css')}}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        @stack('css')
    </head>
    <body class="sb-nav-fixed">
    
    <x-navigation-header />

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <x-navigation-menu />
        </div>
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>

            <x-footer />
        </div>
    </div>

    @stack('js1')

        <!-- Primero carga jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Luego carga DataTables -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
        <!-- Luego carga Chart.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    
        <!-- Tus archivos personalizados -->
        <script src="{{ asset('demo/chart-area-demo.js')}}"></script>
        <script src="{{ asset('demo/chart-bar-demo.js')}}"></script>
    

        <!-- Otros scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/scripts.js')}}"></script>
        <script src="{{ asset('js/datatables-simple-demo.js')}}"></script>

        <!-- Configurar idioma en español para DataTables -->
        <script>
            $(document).ready(function () {
                $('#miTabla').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                    }
                });
            });
        </script>
        
    @stack('js2')

        
    </body>
</html>

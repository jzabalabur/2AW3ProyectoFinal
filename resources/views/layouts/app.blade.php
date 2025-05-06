<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_sinFondo_simbolo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap" rel="stylesheet">
    
    <!-- Cargar el CSS común para todas las páginas -->
    @vite(['resources/css/app.css', 'resources/css/cliente.css', 'resources/css/welcome.css'])



    <!-- Aquí puedes incluir los estilos específicos de cada página -->
    @stack('styles') <!-- Esto se usa para agregar CSS específico por página -->

</head>
<body class="flex flex-col min-h-screen">
    <!-- Header -->
    
        @include('partials.header')  <!-- Aquí incluirás tu archivo de header -->
    

    <!-- Contenido de la página -->
        @yield('content')  <!-- Aquí se mostrará el contenido específico de cada página -->


    <!-- Footer -->
        @include('partials.footer')  <!-- Aquí incluirás tu archivo de footer -->
    

    <!-- Aquí puedes agregar tus scripts JS -->
    @vite(['resources/js/app.js'])
    <script>
        // Pasar las rutas al objeto window global
        window.routes = {
            bienvenida: @json(route('bienvenida')),
            principal: @json(route('principal')),
            contacto: @json(route('contacto')),
            publicar: @json(route('publicar'))
        };
    </script>
    @stack('scripts')
</body>
</html>

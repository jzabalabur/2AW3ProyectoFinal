<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    
    <!-- Cargar el CSS común para todas las páginas -->
    @vite(['resources/css/app.css'])
    @vite(['resources/css/cliente.css'])

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
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

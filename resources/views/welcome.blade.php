@extends('layouts.app')

@section('title', 'Zablo')

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/welcome.css'])
@endpush

@section('content')
<main id="contenedorPagina" class="flex-grow">
    <h1>PAGINA PRINCIPAL</h1>
    <p>NOTA: Todas las paginas del cliente estan hechas a partir del layout resources/views/layouts/app.blade.php</p>
    <p>En ese layout se introducen elementos como el header o el footer que se encuentran en: resources/views/partials</p>
    <p>Ademas, el cuerpo del documento (estas lineas), se encuentran en la carpeta: resources/views/cliente (A excepcion del cuerpo de la pagina principal, que es: resources/views/welcome.blade.php)</p>
    
    <p>ESTILOS: Todas las paginas del cliente comparten un css comun que es cliente.css. Ademas, cada pagina tiene su propio css (welcome.css, perfil.css, diseno.css) que se encuentran en la carpeta resources/css</p>
</main>
@stop


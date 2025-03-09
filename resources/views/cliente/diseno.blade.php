@extends('layouts.app')

@section('title', 'Página de Diseño')

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/diseno.css'])
@endpush

@section('content')
<main class="flex-grow">
    <h1>Página de Diseño</h1>
    <p>Esta es la página de diseño para el cliente.</p>
</main>
@stop

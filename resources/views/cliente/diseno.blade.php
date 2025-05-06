@extends('layouts.app')

@section('title', 'Página de Diseño - Zablo')

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/diseno.css'])
@endpush

@section('content')
<main class="flex-grow">
<div class="main-container">
        <!-- Barra de progreso -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: 20%;"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step active">Inicio</div>
                <div class="progress-step">Bienvenida</div>
                <div class="progress-step">Principal</div>
                <div class="progress-step">Contacto</div>
                <div class="progress-step">Publicar</div>
            </div>
        </div>
        <h3>Configura tu página</h3>
        <p>Nuestro diseñador incluye una página principal donde promocionar tu negocio, tus habilidades o el mensaje que tú decidas. Además, tienes la opción de incluir un mensaje de bienvenida y una página de contacto.</p>

        <label>
            <input type="checkbox" id="welcome-message"> Incluir mensaje de bienvenida
        </label>
        <br>
        <label>
            <input type="checkbox" id="contact-page"> Incluir página de contacto
        </label>
        <br>
        <button id="continuar">Continuar</button>
    </div>
</main>
@stop
@push('scripts')
@vite(['resources/js/diseno.js'])
@endpush
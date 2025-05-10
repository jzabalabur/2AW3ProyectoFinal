@extends('layouts.app')

@section('title', 'Página de Diseño - Zablo')

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/diseno.css'])
    @vite(['resources/css/publicar.css'])

@endpush

@section('content')
<main class="flex-grow">

 <div class="main-container">
        <!-- Barra de progreso -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: 100%;"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step completed">Inicio</div>
                <div class="progress-step completed">Bienvenida</div>
                <div class="progress-step completed">Principal</div>
                <div class="progress-step completed">Contacto</div>
                <div class="progress-step active">Publicar</div>
            </div>
        </div>
        
        <h3>Publica tu página</h3>
        <h1>Paso 5</h1>
        <p>Introduce el dominio que deseas usar para tu página web. Comprueba su disponibilidad y cuando estés listo, pulsa en publicar.</p>

        <div class="domain-check-container">
            <div class="domain-form">
                <div class="form-group">
                    <input type="text" id="domain-input" placeholder="ejemplo.mipagina.com">
                    <button id="check-domain-btn" class="primary-button">Comprobar</button>
                </div>
                
                <div id="domain-result" class="domain-result hidden">
                    <!-- Resultado de la comprobación aparecerá aquí -->
                </div>
                
                <button id="publish-btn" class="primary-button hidden">Publicar Página</button>
            </div>
        </div>
    </div>
</main>
@stop

@push('scripts')
    @vite(['resources/js/publicar.js'])
@endpush
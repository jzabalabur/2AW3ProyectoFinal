@extends('layouts.app')
@section('title', __('publicar.pagina_diseno_titulo'))

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/diseno.css'])
    @vite(['resources/css/publicar.css'])
@endpush

@section('content')
<main class="flex-grow">
    <!-- CSRF Token para JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">    
    <div class="main-container">
        <!-- Barra de progreso -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: 100%;"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step completed">{{ __('publicar.inicio') }}</div>
                <div class="progress-step">{{ __('publicar.bienvenida') }}</div>
                <div class="progress-step">{{ __('publicar.principal') }}</div>
                <div class="progress-step">{{ __('publicar.contacto') }}</div>
                <div class="progress-step active">{{ __('publicar.publicar') }}</div>
            </div>
        </div>
        
        <h3>{{ __('publicar.publica_tu_pagina') }}</h3>
        <h1>{{ __('publicar.paso') }}</h1>
        <p>{{ __('publicar.descripcion_publicar') }}</p>
        
        <div class="domain-check-container">
            <div class="domain-form">
                <div class="form-group">
                    <input type="text" id="domain-input" name="url" placeholder="ejemplo.mipagina.com">
                    <button id="check-domain-btn" class="primary-button">Comprobar</button>
                </div>
                
                <div id="domain-result" class="domain-result hidden">
                    <!-- Resultado de la comprobación aparecerá aquí -->
                </div>
                
                <!-- Botones de acción -->
                <div class="action-buttons">
                    <button id="save-draft-btn" class="secondary-button">💾 Guardar como borrador</button>
                    <button id="publish-btn" class="primary-button hidden">🚀 Publicar Página</button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Scripts para debugging (remover en producción) -->
<script>
    // Debug: Verificar que tenemos datos en localStorage
    console.log('LocalStorage Data Check:');
    console.log('mainPageData:', localStorage.getItem('mainPageData') ? '✓' : '✗');
    console.log('welcomeData:', localStorage.getItem('welcomeData') ? '✓' : '✗');
    console.log('contactData:', localStorage.getItem('contactData') ? '✓' : '✗');
    console.log('welcomeMessage:', localStorage.getItem('welcomeMessage'));
    console.log('contactPage:', localStorage.getItem('contactPage'));
</script>
@stop

@push('scripts')
    @vite(['resources/js/publicar.js'])
@endpush
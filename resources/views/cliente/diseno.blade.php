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
                <div class="progress-step completed">{{ __('diseno.inicio') }}</div>
                <div class="progress-step">{{ __('diseno.bienvenida') }}</div> <!-- Eliminado "active" para que no se vea como un link -->
                <div class="progress-step">{{ __('diseno.principal') }}</div>
                <div class="progress-step">{{ __('diseno.contacto') }}</div>
                <div class="progress-step">{{ __('diseno.publicar') }}</div>
            </div>
        </div>
        <h3>{{ __('diseno.configura_tu_pagina') }}</h3>
        <p>{{ __('diseno.descripcion') }}</p>

        <label>
            <input type="checkbox" id="welcome-message"> {{ __('diseno.incluir_mensaje_bienvenida') }}
        </label>
        <br>
        <label>
            <input type="checkbox" id="contact-page"> {{ __('diseno.incluir_pagina_contacto') }}
        </label>
        <br>
        <button id="continuar">{{ __('diseno.continuar') }}</button>
    </div>
</main>
@stop
@push('scripts')
@vite(['resources/js/diseno.js'])
@endpush
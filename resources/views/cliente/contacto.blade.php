@extends('layouts.app')

@section('title', __('contacto.pagina_diseno_titulo'))

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/diseno.css'])
    @vite(['resources/css/contacto.css'])
@endpush

@section('content')
<div class="main-container">
        <!-- Barra de progreso -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: 60%;"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step completed">{{ __('contacto.inicio') }}</div>
                <div class="progress-step active">{{ __('contacto.bienvenida') }}</div>
                <div class="progress-step">{{ __('contacto.principal') }}</div>
                <div class="progress-step">{{ __('contacto.contacto') }}</div>
                <div class="progress-step">{{ __('contacto.publicar') }}</div>
            </div>
        </div>
        
        <div class="design-container">
            <!-- Contenedor de vista previa -->
            <div class="preview-container">
                <h3>{{ __('contacto.vista_previa') }}</h3>
                <div id="preview" class="preview"></div>
            </div>

            <!-- Contenedor del formulario -->
            <div class="form-container">
                <h3>{{ __('contacto.diseno_contacto') }}</h3>
                <form id="design-form">
                    <div class="form-section">
                        <h3>{{ __('contacto.informacion_contacto') }}</h3>
                        <p>{{ __('contacto.selecciona_informacion') }}</p>
                        <div id="contact-options"></div>
                    </div>

                    <div class="form-section">
                        <h3>{{ __('contacto.mapa_ubicacion') }}</h3>
                        <div class="form-group">
                            <input type="checkbox" id="show-map" name="show-map">
                            <label for="show-map">{{ __('contacto.mostrar_mapa') }}</label>
                        </div>
                        <div class="form-group">
                            <label for="map-address">{{ __('contacto.direccion_mapa') }}</label>
                            <input type="text" id="map-address" class="form-control" placeholder="{{ __('contacto.placeholder_direccion') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Botones -->
        <div class="button-container">
            <button type="button" id="reset-btn" class="reset-button">🗑️ {{ __('contacto.empezar_de_cero') }}</button>
            <button type="button" id="continue-btn" class="primary-button">{{ __('contacto.continuar') }}</button>
        </div>
    </div>

    <!-- Modal de confirmación para reset -->
    <div id="reset-modal" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <h3 class="modal-title">{{ __('contacto.seguro_reset') }}</h3>
            <p class="modal-message">{{ __('contacto.mensaje_reset') }}</p>
            <div class="modal-actions">
                <button id="cancel-reset" class="modal-button cancel">{{ __('contacto.cancelar') }}</button>
                <button id="confirm-reset" class="modal-button confirm">{{ __('contacto.borrar_todo') }}</button>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    @vite(['resources/js/contacto.js'])
@endpush

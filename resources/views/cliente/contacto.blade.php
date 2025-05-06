@extends('layouts.app')

@section('title', 'Página de Diseño - Zablo')

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
                <div class="progress-step completed">Inicio</div>
                <div class="progress-step completed">Bienvenida</div>
                <div class="progress-step completed">Principal</div>
                <div class="progress-step active">Contacto</div>
                <div class="progress-step">Publicar</div>
            </div>
        </div>
        
        <div class="design-container">
            <!-- Contenedor de vista previa -->
            <div class="preview-container">
                <h3>Vista Previa</h3>
                <div id="preview" class="preview"></div>
            </div>

            <!-- Contenedor del formulario -->
            <div class="form-container">
                <h3>Diseñar página de contacto</h3>
                <form id="design-form">
                    <div class="form-section">
                        <h3>Información de Contacto</h3>
                        <p>Selecciona qué información mostrar:</p>
                        <div id="contact-options"></div>
                    </div>

                    <div class="form-section">
                        <h3>Mapa de Ubicación</h3>
                        <div class="form-group">
                            <input type="checkbox" id="show-map" name="show-map">
                            <label for="show-map">Mostrar mapa de ubicación</label>
                        </div>
                        <div class="form-group">
                            <label for="map-address">Dirección para el mapa:</label>
                            <input type="text" id="map-address" class="form-control" placeholder="Ej: Calle Principal 123, Ciudad">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Botones -->
        <div class="button-container">
            <button type="button" id="reset-btn" class="reset-button">🗑️ Empezar de cero</button>
            <button type="button" id="continue-btn" class="primary-button">Continuar</button>
        </div>
    </div>

    <!-- Modal de confirmación para reset -->
    <div id="reset-modal" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <h3 class="modal-title">¿Estás seguro?</h3>
            <p class="modal-message">Esta acción borrará todo tu diseño actual. ¿Deseas continuar?</p>
            <div class="modal-actions">
                <button id="cancel-reset" class="modal-button cancel">Cancelar</button>
                <button id="confirm-reset" class="modal-button confirm">Borrar todo</button>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    @vite(['resources/js/contacto.js'])
@endpush
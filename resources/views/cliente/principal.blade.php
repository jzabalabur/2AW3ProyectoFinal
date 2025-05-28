@extends('layouts.app')

@section('title', __('principal.pagina_diseno'))

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/diseno.css'])
    @vite(['resources/css/principal.css'])
@endpush

@section('content')
<div class="main-container">
        <!-- Barra de progreso -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: 40%;"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step completed">{{ __('principal.inicio') }}</div>
                <div class="progress-step">{{ __('principal.bienvenida') }}</div>
                <div class="progress-step">{{ __('principal.principal') }}</div>
                <div class="progress-step">{{ __('principal.contacto') }}</div>
                <div class="progress-step">{{ __('principal.publicar') }}</div>
            </div>
        </div>
        
        <div class="design-container">
            <!-- Contenedor de vista previa -->
            <div class="preview-container">
                <h3>{{ __('principal.vista_previa') }}</h3>
                <div id="preview" class="preview"></div>
            </div>

            <!-- Contenedor del formulario -->
            <div class="form-container">
                <h3>{{ __('principal.disenar_pagina_principal') }}</h3>
                <form id="design-form">
                    <!-- Sección 1: Cabecera -->
                    <div class="form-section">
                        <h3>{{ __('principal.cabecera') }}</h3>
                        <label for="header-text">{{ __('principal.texto_header') }}</label>
                        <input type="text" id="header-text" placeholder="{{ __('principal.escribe_texto_header') }}">
                        
                        <label for="header-bg-color">{{ __('principal.color_fondo') }}</label>
                        <input type="color" id="header-bg-color" value="#f8f8f8">
                        
                        <label for="header-text-color">{{ __('principal.color_texto') }}</label>
                        <input type="color" id="header-text-color" value="#000000">
                    </div>

                    <!-- Sección 2: Logo -->
                    <div class="form-section">
                        <h3>{{ __('principal.logotipo') }}</h3>
                        <label for="logo">{{ __('principal.subir_logotipo') }}</label>
                        <input type="file" id="logo" accept="image/*">
                        
                        <label for="logo-position">{{ __('principal.posicion_logotipo') }}</label>
                        <select id="logo-position">
                            <option value="center">{{ __('principal.centro') }}</option>
                            <option value="left">{{ __('principal.izquierda') }}</option>
                        </select>
                    </div>

                    <!-- Sección 3: Cuerpo -->
                    <div class="form-section">
                        <h3>{{ __('principal.cuerpo_principal') }}</h3>
                        <label for="bg-color">{{ __('principal.color_fondo') }}</label>
                        <input type="color" id="bg-color" value="#ffffff">
                        
                        <label for="text-color">{{ __('principal.color_texto') }}</label>
                        <input type="color" id="text-color" value="#000000">
                        
                        <label for="font-family">{{ __('principal.fuente_texto') }}</label>
                        <select id="font-family">
                            <option value="Arial, sans-serif">Arial</option>
                            <option value="Georgia, serif">Georgia</option>
                            <option value="Courier New, monospace">Courier New</option>
                            <option value="Verdana, sans-serif">Verdana</option>
                            <option value="Times New Roman, serif">Times New Roman</option>
                        </select>
                    </div>

                    <!-- Sección 4: Imagen Principal -->
                    <div class="form-section">
                        <h3>{{ __('principal.imagen_principal') }}</h3>
                        <label for="main-photo">{{ __('principal.foto_principal') }} (4:1):</label>
                        <input type="file" id="main-photo" accept="image/*">
                        
                        <label for="photo-title">{{ __('principal.titulo_foto') }}</label>
                        <input type="text" id="photo-title" placeholder="{{ __('principal.escribe_titulo') }}">
                        
                        <label for="photo-description">{{ __('principal.parrafo_descriptivo') }}</label>
                        <textarea id="photo-description" placeholder="{{ __('principal.escribe_descripcion') }}"></textarea>
                        
                        <label for="photo-description-align">{{ __('principal.alineacion_parrafo') }}</label>
                        <select id="photo-description-align">
                            <option value="justify">{{ __('principal.justificado') }}</option>
                            <option value="center">{{ __('principal.centrado') }}</option>
                            <option value="left">{{ __('principal.izquierda') }}</option>
                            <option value="right">{{ __('principal.derecha') }}</option>
                        </select>
                    </div>

                    <!-- Sección 5: Contenido Intermedio -->
                    <div class="form-section">
                        <h3>{{ __('principal.contenido_intermedio') }}</h3>
                        
                        <label>{{ __('principal.tipo_contenido') }}</label>
                        <select id="content-type">
                            <option value="none">{{ __('principal.ninguno') }}</option>
                            <option value="feature-module">{{ __('principal.modulo_destacable') }}</option>
                            <option value="video">{{ __('principal.video_embedido') }}</option>
                            <option value="map">{{ __('principal.mapa_interactivo') }}</option>
                        </select>

                        <!-- Opción: Módulo destacable -->
                        <div id="feature-module-options" class="content-options" style="display:none;">
                            <h4>{{ __('principal.opciones_modulo') }}</h4>
                            <div class="feature-columns">
                                <!-- Columna 1 -->
                                <div class="feature-column">
                                    <label>{{ __('principal.icono_columna_1') }}</label>
                                    <select class="icon-select">
                                        <option value="star">{{ __('principal.estrella') }}</option>
                                        <option value="shield">{{ __('principal.escudo') }}</option>
                                        <option value="trophy">{{ __('principal.trofeo') }}</option>
                                        <option value="lightbulb">{{ __('principal.bombilla') }}</option>
                                        <option value="heart">{{ __('principal.corazon') }}</option>
                                    </select>
                                    <label>{{ __('principal.texto_columna_1') }}</label>
                                    <input type="text" class="feature-text" placeholder="{{ __('principal.texto_descriptivo') }}">
                                </div>
                                <!-- Columna 2 -->
                                <div class="feature-column">
                                    <label>{{ __('principal.icono_columna_2') }}</label>
                                    <select class="icon-select">
                                        <option value="star">{{ __('principal.estrella') }}</option>
                                        <option value="shield">{{ __('principal.escudo') }}</option>
                                        <option value="trophy">{{ __('principal.trofeo') }}</option>
                                        <option value="lightbulb">{{ __('principal.bombilla') }}</option>
                                        <option value="heart">{{ __('principal.corazon') }}</option>
                                    </select>
                                    <label>{{ __('principal.texto_columna_2') }}</label>
                                    <input type="text" class="feature-text" placeholder="{{ __('principal.texto_descriptivo') }}">
                                </div>
                                <!-- Columna 3 -->
                                <div class="feature-column">
                                    <label>{{ __('principal.icono_columna_3') }}</label>
                                    <select class="icon-select">
                                        <option value="star">{{ __('principal.estrella') }}</option>
                                        <option value="shield">{{ __('principal.escudo') }}</option>
                                        <option value="trophy">{{ __('principal.trofeo') }}</option>
                                        <option value="lightbulb">{{ __('principal.bombilla') }}</option>
                                        <option value="heart">{{ __('principal.corazon') }}</option>
                                    </select>
                                    <label>{{ __('principal.texto_columna_3') }}</label>
                                    <input type="text" class="feature-text" placeholder="{{ __('principal.texto_descriptivo') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Opción: Vídeo -->
                        <div id="video-options" class="content-options" style="display:none;">
                            <h4>{{ __('principal.opciones_video') }}</h4>
                            <label>{{ __('principal.url_video') }}</label>
                            <input type="text" id="video-url" placeholder="https://www.youtube.com/watch?v=...">
                            <label>{{ __('principal.texto_descriptivo') }}</label>
                            <textarea id="video-description" placeholder="{{ __('principal.descripcion_video') }}"></textarea>
                        </div>

                        <!-- Opción: Mapa -->
                        <div id="map-options" class="content-options" style="display:none;">
                            <h4>{{ __('principal.opciones_mapa') }}</h4>
                            <label>{{ __('principal.direccion') }}</label>
                            <input type="text" id="map-address" placeholder="{{ __('principal.ej_direccion') }}">
                            <label>{{ __('principal.texto_descriptivo') }}</label>
                            <textarea id="map-description" placeholder="{{ __('principal.descripcion_mapa') }}"></textarea>
                        </div>
                    </div>

                    <!-- Sección 6: Pie de página -->
                    <div class="form-section">
                        <h3>{{ __('principal.pie_pagina') }}</h3>
                        <label for="footer-text">{{ __('principal.texto_footer') }}</label>
                        <input type="text" id="footer-text" placeholder="{{ __('principal.escribe_texto_footer') }}">
                        
                        <label for="footer-bg-color">{{ __('principal.color_fondo') }}</label>
                        <input type="color" id="footer-bg-color" value="#f8f8f8">
                        
                        <label for="footer-text-color">{{ __('principal.color_texto') }}</label>
                        <input type="color" id="footer-text-color" value="#000000">
                    </div>
                </form>
            </div>
        </div>
                            <!-- Botones -->
                            <div class="button-container">
                                <button type="button" id="reset-btn" class="reset-button">🗑️ {{ __('principal.empezar_de_cero') }}</button>
                                <button type="button" id="continue-btn" class="primary-button">{{ __('principal.continuar') }}</button>
                            </div>
    </div>

    <!-- Modal de confirmación para reset -->
    <div id="reset-modal" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <h3 class="modal-title">{{ __('principal.estás_seguro') }}</h3>
            <p class="modal-message">{{ __('principal.borrar_todo') }}</p>
            <div class="modal-actions">
                <button id="cancel-reset" class="modal-button cancel">{{ __('principal.cancelar') }}</button>
                <button id="confirm-reset" class="modal-button confirm">{{ __('principal.borrar_todo') }}</button>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    @vite(['resources/js/principal.js'])
@endpush

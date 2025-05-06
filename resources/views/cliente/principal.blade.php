@extends('layouts.app')

@section('title', 'Página de Diseño - Zablo')

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
                <div class="progress-step completed">Inicio</div>
                <div class="progress-step completed">Bienvenida</div>
                <div class="progress-step active">Principal</div>
                <div class="progress-step">Contacto</div>
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
                <h3>Diseñar página principal</h3>
                <form id="design-form">
                    <!-- Sección 1: Cabecera -->
                    <div class="form-section">
                        <h3>Cabecera</h3>
                        <label for="header-text">Texto del Header:</label>
                        <input type="text" id="header-text" placeholder="Escribe el texto del Header">
                        
                        <label for="header-bg-color">Color de fondo:</label>
                        <input type="color" id="header-bg-color" value="#f8f8f8">
                        
                        <label for="header-text-color">Color del texto:</label>
                        <input type="color" id="header-text-color" value="#000000">
                    </div>

                    <!-- Sección 2: Logo -->
                    <div class="form-section">
                        <h3>Logotipo</h3>
                        <label for="logo">Subir logotipo:</label>
                        <input type="file" id="logo" accept="image/*">
                        
                        <label for="logo-position">Posición del logotipo:</label>
                        <select id="logo-position">
                            <option value="center">Centro</option>
                            <option value="left">Izquierda</option>
                        </select>
                    </div>

                    <!-- Sección 3: Cuerpo -->
                    <div class="form-section">
                        <h3>Cuerpo Principal</h3>
                        <label for="bg-color">Color de fondo:</label>
                        <input type="color" id="bg-color" value="#ffffff">
                        
                        <label for="text-color">Color del texto:</label>
                        <input type="color" id="text-color" value="#000000">
                        
                        <label for="font-family">Fuente del texto:</label>
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
                        <h3>Imagen Principal</h3>
                        <label for="main-photo">Foto principal (4:1):</label>
                        <input type="file" id="main-photo" accept="image/*">
                        
                        <label for="photo-title">Título sobre la foto:</label>
                        <input type="text" id="photo-title" placeholder="Escribe el título">
                        
                        <label for="photo-description">Párrafo descriptivo:</label>
                        <textarea id="photo-description" placeholder="Escribe una descripción"></textarea>
                        
                        <label for="photo-description-align">Alineación del párrafo:</label>
                        <select id="photo-description-align">
                            <option value="justify">Justificado</option>
                            <option value="center">Centrado</option>
                            <option value="left">Izquierda</option>
                            <option value="right">Derecha</option>
                        </select>
                    </div>
<!-- Sección 5: Contenido Intermedio (nueva) -->
<div class="form-section">
    <h3>Contenido Intermedio</h3>
    
    <label>Tipo de contenido:</label>
    <select id="content-type">
        <option value="none">Ninguno</option>
        <option value="feature-module">Módulo destacable</option>
        <option value="video">Vídeo embedido</option>
        <option value="map">Mapa interactivo</option>
    </select>

    <!-- Opción: Módulo destacable -->
    <div id="feature-module-options" class="content-options" style="display:none;">
        <h4>Opciones del Módulo</h4>
        <div class="feature-columns">
            <!-- Columna 1 -->
            <div class="feature-column">
                <label>Icono Columna 1:</label>
                <select class="icon-select">
                    <option value="star">Estrella</option>
                    <option value="shield">Escudo</option>
                    <option value="trophy">Trofeo</option>
                    <option value="lightbulb">Bombilla</option>
                    <option value="heart">Corazón</option>
                </select>
                <label>Texto Columna 1:</label>
                <input type="text" class="feature-text" placeholder="Texto descriptivo">
            </div>
            <!-- Columna 2 -->
            <div class="feature-column">
                <label>Icono Columna 2:</label>
                <select class="icon-select">
                    <option value="star">Estrella</option>
                    <option value="shield">Escudo</option>
                    <option value="trophy">Trofeo</option>
                    <option value="lightbulb">Bombilla</option>
                    <option value="heart">Corazón</option>
                </select>
                <label>Texto Columna 2:</label>
                <input type="text" class="feature-text" placeholder="Texto descriptivo">
            </div>
            <!-- Columna 3 -->
            <div class="feature-column">
                <label>Icono Columna 3:</label>
                <select class="icon-select">
                    <option value="star">Estrella</option>
                    <option value="shield">Escudo</option>
                    <option value="trophy">Trofeo</option>
                    <option value="lightbulb">Bombilla</option>
                    <option value="heart">Corazón</option>
                </select>
                <label>Texto Columna 3:</label>
                <input type="text" class="feature-text" placeholder="Texto descriptivo">
            </div>
        </div>
    </div>

    <!-- Opción: Vídeo -->
    <div id="video-options" class="content-options" style="display:none;">
        <h4>Opciones del Vídeo</h4>
        <label>URL del vídeo (YouTube/Vimeo):</label>
        <input type="text" id="video-url" placeholder="https://www.youtube.com/watch?v=...">
        <label>Texto descriptivo:</label>
        <textarea id="video-description" placeholder="Descripción del vídeo"></textarea>
    </div>

    <!-- Opción: Mapa -->
    <div id="map-options" class="content-options" style="display:none;">
        <h4>Opciones del Mapa</h4>
        <label>Dirección:</label>
        <input type="text" id="map-address" placeholder="Ej: Calle Mayor, 10, Madrid">
        <label>Texto descriptivo:</label>
        <textarea id="map-description" placeholder="Cómo llegar o información adicional"></textarea>
    </div>
</div>
                    <!-- Sección 5: Pie de página -->
                    <div class="form-section">
                        <h3>Pie de Página</h3>
                        <label for="footer-text">Texto del Footer:</label>
                        <input type="text" id="footer-text" placeholder="Escribe el texto del Footer">
                        
                        <label for="footer-bg-color">Color de fondo:</label>
                        <input type="color" id="footer-bg-color" value="#f8f8f8">
                        
                        <label for="footer-text-color">Color del texto:</label>
                        <input type="color" id="footer-text-color" value="#000000">
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
    @vite(['resources/js/principal.js'])
@endpush
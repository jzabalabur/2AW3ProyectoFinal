@extends('layouts.app')

@section('title', __('bienvenida.pagina_diseno_titulo'))

@push('styles')
    <!--CSS específico de la página-->
    @vite(['resources/css/diseno.css'])
    @vite(['resources/css/bienvenida.css'])
@endpush

@section('content')
<div class="main-container">
    <!-- Barra de progreso -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress" style="width: 20%;"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed">{{ __('bienvenida.inicio') }}</div>
            <div class="progress-step">{{ __('bienvenida.bienvenida') }}</div>
            <div class="progress-step">{{ __('bienvenida.principal') }}</div>
            <div class="progress-step">{{ __('bienvenida.contacto') }}</div>
            <div class="progress-step">{{ __('bienvenida.publicar') }}</div>
        </div>
    </div>
    <div class="design-container">
        <!-- Vista previa -->
        <div class="preview-container">
            <h3>{{ __('bienvenida.vista_previa') }}</h3>
            <div id="preview" class="preview">
                <img class="background-image" id="background-image" src="" alt="{{ __('bienvenida.fondo') }}" style="display: none;">
                <div class="content"></div>
                <button id="enter-button">{{ __('bienvenida.entrar_web') }}</button>
            </div>
        </div>

        <!-- Formulario -->
        <div class="form-container">
            <h3>{{ __('bienvenida.diseno_bienvenida') }}</h3>

            <!-- Sección: Bienvenida -->
            <div class="form-section">
                <h3>{{ __('bienvenida.texto_bienvenida') }}</h3>
                <label for="welcome-title">{{ __('bienvenida.titulo') }}</label>
                <input type="text" id="welcome-title" placeholder="{{ __('bienvenida.placeholder_titulo') }}">

                <label for="welcome-message">{{ __('bienvenida.mensaje') }}</label>
                <textarea id="welcome-message" placeholder="{{ __('bienvenida.placeholder_mensaje') }}"></textarea>
            </div>

            <!-- Sección: Fuente -->
            <div class="form-section">
                <h3>{{ __('bienvenida.fuente') }}</h3>
                <label for="font-family">{{ __('bienvenida.fuente_texto') }}</label>
                <select id="font-family">
                    <option value="Arial, sans-serif">Arial</option>
                    <option value="Georgia, serif">Georgia</option>
                    <option value="Courier New, monospace">Courier New</option>
                    <option value="Verdana, sans-serif">Verdana</option>
                    <option value="Times New Roman, serif">Times New Roman</option>
                </select>
            </div>

            <!-- Sección: Fondo -->
            <div class="form-section">
                <h3>{{ __('bienvenida.fondo') }}</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="background-type" value="color" checked> {{ __('bienvenida.color_fondo') }}
                    </label>
                    <label>
                        <input type="radio" name="background-type" value="image"> {{ __('bienvenida.imagen_fondo') }}
                    </label>
                </div>

                <div id="color-background-controls">
                    <label for="welcome-bg-color">{{ __('bienvenida.color_fondo') }}</label>
                    <input type="color" id="welcome-bg-color" value="#ffffff">
                </div>

                <div id="image-background-controls" style="display: none;">
                    <label for="background-image-input">{{ __('bienvenida.subir_imagen_fondo') }}</label>
                    <input type="file" id="background-image-input" accept="image/*">
                </div>
            </div>

            <!-- Sección: Logo -->
            <div class="form-section">
                <h3>{{ __('bienvenida.logo') }}</h3>
                <label for="logo">{{ __('bienvenida.subir_logo') }}</label>
                <input type="file" id="logo" accept="image/*">

                <label for="logo-size">{{ __('bienvenida.tamano_logo') }}</label>
                <input type="number" id="logo-size" value="100" min="50" max="300">

                <div class="logo-position-container">
                    <label for="logo-position">{{ __('bienvenida.posicion_logo') }}</label>
                    <select id="logo-position">
                        <option value="center">{{ __('bienvenida.centro') }}</option>
                        <option value="left">{{ __('bienvenida.izquierda') }}</option>
                        <option value="right">{{ __('bienvenida.derecha') }}</option>
                    </select>
                </div>
            </div>

            <!-- Sección: Botón -->
            <div class="form-section">
                <h3>{{ __('bienvenida.boton') }}</h3>
                <label for="button-text">{{ __('bienvenida.texto_boton') }}</label>
                <input type="text" id="button-text" placeholder="{{ __('bienvenida.placeholder_boton') }}">

                <label for="button-color">{{ __('bienvenida.color_boton') }}</label>
                <input type="color" id="button-color" value="#0000ff">

                <label for="button-text-color">{{ __('bienvenida.color_texto_boton') }}</label>
                <input type="color" id="button-text-color" value="#ffffff">

                <label for="button-font-size">{{ __('bienvenida.tamano_texto_boton') }}</label>
                <input type="number" id="button-font-size" value="16" min="10" max="50">

                <label for="button-padding">{{ __('bienvenida.tamano_boton') }}</label>
                <input type="number" id="button-padding" value="10" min="5" max="30">
            </div>

            <!-- Sección: Contenido -->
            <div class="form-section">
                <h3>{{ __('bienvenida.contenido') }}</h3>
                <label for="content-bg-color">{{ __('bienvenida.color_fondo_contenido') }}</label>
                <input type="color" id="content-bg-color" value="#ffffff">

                <label for="content-bg-opacity">{{ __('bienvenida.transparencia_fondo_contenido') }}</label>
                <input type="number" id="content-bg-opacity" value="80" min="0" max="100">

                <label for="content-text-color">{{ __('bienvenida.color_texto_contenido') }}</label>
                <input type="color" id="content-text-color" value="#000000">

                <label for="title-font-size">{{ __('bienvenida.tamano_titulo') }}</label>
                <input type="number" id="title-font-size" value="24" min="10" max="50">

                <label for="paragraph-font-size">{{ __('bienvenida.tamano_parrafo') }}</label>
                <input type="number" id="paragraph-font-size" value="16" min="10" max="50">

                <div class="text-style-container">
                    <label>
                        <input type="checkbox" id="title-bold"> {{ __('bienvenida.negrita_titulo') }}
                    </label>
                    <label>
                        <input type="checkbox" id="title-italic"> {{ __('bienvenida.cursiva_titulo') }}
                    </label>
                </div>

                <div class="text-style-container">
                    <label>
                        <input type="checkbox" id="paragraph-bold"> {{ __('bienvenida.negrita_parrafo') }}
                    </label>
                    <label>
                        <input type="checkbox" id="paragraph-italic"> {{ __('bienvenida.cursiva_parrafo') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="button-container">
        <button id="reset-button" class="reset-button">🗑️ {{ __('bienvenida.empezar_de_cero') }}</button>
        <button id="continuar">{{ __('bienvenida.continuar') }}</button>
    </div>
</div>

<!-- Modal -->
<div id="reset-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3 class="modal-title">{{ __('bienvenida.seguro_reset') }}</h3>
        <p class="modal-message">{{ __('bienvenida.mensaje_reset') }}</p>
        <div class="modal-actions">
            <button id="cancel-reset" class="modal-button cancel">{{ __('bienvenida.cancelar') }}</button>
            <button id="confirm-reset" class="modal-button confirm">{{ __('bienvenida.borrar_todo') }}</button>
        </div>
    </div>
</div>
@stop

@push('scripts')
    @vite(['resources/js/bienvenida.js'])
@endpush

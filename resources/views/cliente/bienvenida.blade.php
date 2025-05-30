@extends('layouts.app')

@section('title', isset($web) ? 'Editar Bienvenida - ' . $web->name : __('bienvenida.pagina_diseno_titulo'))

@push('styles')
    @vite(['resources/css/diseno.css'])
    @vite(['resources/css/bienvenida.css'])
@endpush

@section('content')
<div class="main-container">
    @if(isset($web))
        <!-- Navegación para edición -->
        <div class="edit-navigation">
            <a href="{{ route('webs.edit', $web) }}" class="back-link">← Volver a edición de web</a>
            <h2>Editando página de bienvenida: {{ $web->name }}</h2>
        </div>
    @endif

    <!-- Barra de progreso -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress" style="width: 40%;"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed">{{ __('bienvenida.inicio') }}</div>
            <div class="progress-step active">{{ __('bienvenida.bienvenida') }}</div>
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

            <form id="welcome-form" enctype="multipart/form-data">
                @csrf

                <!-- Sección: Bienvenida -->
                <div class="form-section">
                    <h3>{{ __('bienvenida.texto_bienvenida') }}</h3>
                    <label for="welcome-title">{{ __('bienvenida.titulo') }}</label>
                    <input type="text" id="welcome-title" name="welcome_title" 
                           placeholder="{{ __('bienvenida.placeholder_titulo') }}">

                    <label for="welcome-message">{{ __('bienvenida.mensaje') }}</label>
                    <textarea id="welcome-message" name="welcome_message" 
                              placeholder="{{ __('bienvenida.placeholder_mensaje') }}"></textarea>
                </div>

                <!-- Sección: Fuente -->
                <div class="form-section">
                    <h3>{{ __('bienvenida.fuente') }}</h3>
                    <label for="font-family">{{ __('bienvenida.fuente_texto') }}</label>
                    <select id="font-family" name="font_family">
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
                    <div class="background-type-container">
                        <label>
                            <input type="radio" name="background-type" value="color" checked>
                            {{ __('bienvenida.color_solido') }}
                        </label>
                        <label>
                            <input type="radio" name="background-type" value="image">
                            {{ __('bienvenida.imagen_fondo') }}
                        </label>
                    </div>

                    <div id="color-background-controls" class="background-controls">
                        <label for="welcome-bg-color">{{ __('bienvenida.color_fondo') }}</label>
                        <input type="color" id="welcome-bg-color" name="background_color" value="#ffffff">
                    </div>

                    <div id="image-background-controls" class="background-controls" style="display: none;">
                        <label for="background-image-input">{{ __('bienvenida.imagen_fondo') }}</label>
                        <input type="file" id="background-image-input" name="background_image" accept="image/*">
                    </div>
                </div>

                <!-- Sección: Logo -->
                <div class="form-section">
                    <h3>{{ __('bienvenida.logotipo') }}</h3>
                    <label for="logo">{{ __('bienvenida.subir_logotipo') }}</label>
                    <input type="file" id="logo" name="logo" accept="image/*">
                    
                    <label for="logo-size">{{ __('bienvenida.tamano_logo') }}</label>
                    <input type="range" id="logo-size" name="logo_size" min="50" max="200" value="100">
                    <span id="logo-size-value">100px</span>
                    
                    <label for="logo-position">{{ __('bienvenida.posicion_logo') }}</label>
                    <select id="logo-position" name="logo_position">
                        <option value="center">{{ __('bienvenida.centro') }}</option>
                        <option value="left">{{ __('bienvenida.izquierda') }}</option>
                        <option value="right">{{ __('bienvenida.derecha') }}</option>
                    </select>
                </div>

                <!-- Sección: Botón -->
                <div class="form-section">
                    <h3>{{ __('bienvenida.boton_entrada') }}</h3>
                    <label for="button-text">{{ __('bienvenida.texto_boton') }}</label>
                    <input type="text" id="button-text" name="button_text" placeholder="{{ __('bienvenida.entrar_web') }}">
                    
                    <label for="button-color">{{ __('bienvenida.color_boton') }}</label>
                    <input type="color" id="button-color" name="button_color" value="#0000ff">
                    
                    <label for="button-text-color">{{ __('bienvenida.color_texto_boton') }}</label>
                    <input type="color" id="button-text-color" name="button_text_color" value="#ffffff">
                    
                    <label for="button-font-size">{{ __('bienvenida.tamano_fuente_boton') }}</label>
                    <input type="range" id="button-font-size" name="button_font_size" min="12" max="24" value="16">
                    <span id="button-font-size-value">16px</span>
                    
                    <label for="button-padding">{{ __('bienvenida.relleno_boton') }}</label>
                    <input type="range" id="button-padding" name="button_padding" min="5" max="20" value="10">
                    <span id="button-padding-value">10px</span>
                </div>

                <!-- Sección: Contenido -->
                <div class="form-section">
                    <h3>{{ __('bienvenida.personalizar_contenido') }}</h3>
                    <label for="content-bg-color">{{ __('bienvenida.color_fondo_contenido') }}</label>
                    <input type="color" id="content-bg-color" name="content_bg_color" value="#ffffff">
                    
                    <label for="content-bg-opacity">{{ __('bienvenida.opacidad_fondo') }}</label>
                    <input type="range" id="content-bg-opacity" name="content_bg_opacity" min="0" max="100" value="80">
                    <span id="content-bg-opacity-value">80%</span>
                    
                    <label for="content-text-color">{{ __('bienvenida.color_texto_contenido') }}</label>
                    <input type="color" id="content-text-color" name="content_text_color" value="#000000">
                </div>

                <!-- Sección: Tipografía -->
                <div class="form-section">
                    <h3>{{ __('bienvenida.tipografia') }}</h3>
                    
                    <div class="typography-group">
                        <h4>{{ __('bienvenida.titulo') }}</h4>
                        <label for="title-font-size">{{ __('bienvenida.tamano_fuente') }}</label>
                        <input type="range" id="title-font-size" name="title_font_size" min="16" max="36" value="24">
                        <span id="title-font-size-value">24px</span>
                        
                        <div class="font-style-controls">
                            <label>
                                <input type="checkbox" id="title-bold" name="title_bold">
                                {{ __('bienvenida.negrita') }}
                            </label>
                            <label>
                                <input type="checkbox" id="title-italic" name="title_italic">
                                {{ __('bienvenida.cursiva') }}
                            </label>
                        </div>
                    </div>

                    <div class="typography-group">
                        <h4>{{ __('bienvenida.parrafo') }}</h4>
                        <label for="paragraph-font-size">{{ __('bienvenida.tamano_fuente') }}</label>
                        <input type="range" id="paragraph-font-size" name="paragraph_font_size" min="12" max="24" value="16">
                        <span id="paragraph-font-size-value">16px</span>
                        
                        <div class="font-style-controls">
                            <label>
                                <input type="checkbox" id="paragraph-bold" name="paragraph_bold">
                                {{ __('bienvenida.negrita') }}
                            </label>
                            <label>
                                <input type="checkbox" id="paragraph-italic" name="paragraph_italic">
                                {{ __('bienvenida.cursiva') }}
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="button-container">
        <button id="reset-button" class="reset-button">🗑️ {{ __('bienvenida.empezar_de_cero') }}</button>
        @if(isset($web))
            <button id="guardar-cambios" class="primary-button">Guardar Cambios</button>
        @endif
        <button id="continuar" class="primary-button">{{ __('bienvenida.continuar') }}</button>
    </div>
</div>

<!-- Modal de confirmación para reset -->
<div id="reset-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3 class="modal-title">¿Estás seguro?</h3>
        <p class="modal-message">Esta acción borrará todo el diseño actual y no se puede deshacer.</p>
        <div class="modal-actions">
            <button id="cancel-reset" class="modal-button cancel">Cancelar</button>
            <button id="confirm-reset" class="modal-button confirm">Borrar todo</button>
        </div>
    </div>
</div>
@stop

@push('scripts')
@vite(['resources/js/bienvenida.js'])
<script>
@if(isset($web))
// Configuración para modo edición
window.webData = {
    id: {{ $web->id }},
    welcome_page_data: @json(is_string($web->welcome_page_data) ? json_decode($web->welcome_page_data, true) : $web->welcome_page_data ?? []),
    isEditing: true,
    updateUrl: '{{ route("webs.update.welcome", $web) }}',
    editMainUrl: '{{ route("webs.edit.main", $web) }}',
    editContactUrl: '{{ route("webs.edit.contact", $web) }}',
    editUrl: '{{ route("webs.edit", $web) }}',
    hasContactPage: {{ $web->hasContactPage() ? 'true' : 'false' }}
};

// Cargar imagen de fondo existente si existe
@php
    $welcomeData = is_string($web->welcome_page_data) ? json_decode($web->welcome_page_data, true) : $web->welcome_page_data ?? [];
@endphp

@if($welcomeData && isset($welcomeData['background_image_path']))
document.addEventListener('DOMContentLoaded', function() {
    const backgroundImage = document.getElementById('background-image');
    if (backgroundImage) {
        backgroundImage.src = '{{ asset("storage/" . $welcomeData["background_image_path"]) }}';
        backgroundImage.style.display = 'block';
    }
});
@endif

@else
// Modo creación normal
window.webData = {
    isEditing: false
};

// Configurar rutas para modo creación normal
window.routes = {
    principal: '{{ route("principal") }}'
};

@endif
</script>
@endpush
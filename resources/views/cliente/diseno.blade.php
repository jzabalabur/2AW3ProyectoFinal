@extends('layouts.app')
@section('title', isset($web) ? 'Editar Configuración - ' . $web->name : 'Página de Diseño - Zablo')
@push('styles')
    @vite(['resources/css/diseno.css'])
@endpush
@section('content')
<main class="flex-grow">
<div class="main-container">
        @if(isset($web))
            <!-- Navegación para edición -->
            <div class="edit-navigation">
                <a href="{{ route('webs.edit', $web) }}" class="back-link">← Volver a edición de web</a>
                <h2>Editando configuración: {{ $web->name }}</h2>
            </div>
        @endif

        <!-- Barra de progreso -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: 20%;"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step completed">{{ __('diseno.inicio') }}</div>
                <div class="progress-step">{{ __('diseno.bienvenida') }}</div>
                <div class="progress-step">{{ __('diseno.principal') }}</div>
                <div class="progress-step">{{ __('diseno.contacto') }}</div>
                <div class="progress-step">{{ __('diseno.publicar') }}</div>
            </div>
        </div>
        
        <h3>{{ __('diseno.configura_tu_pagina') }}</h3>
        <p>{{ __('diseno.descripcion') }}</p>
        
        <form id="design-config-form">
            @csrf
            <div class="form-group">
                <label for="web-name">{{ __('diseno.nombre_web') }}</label>
                <input type="text" 
                    id="web-name" 
                    name="web_name" 
                    class="form-input"
                    placeholder="{{ __('diseno.ayuda_nombre') }}"
                    value="@if(isset($web)){{ $web->name }}@endif"
                    required>
            </div>
                        
            <label>
                <input type="checkbox" id="welcome-message" 
                    @if(isset($web) && $web->design_config && isset($web->design_config['welcomeMessage']) && $web->design_config['welcomeMessage']) checked @endif> 
                {{ __('diseno.incluir_mensaje_bienvenida') }}
            </label>
            <br>
            <label>
                <input type="checkbox" id="contact-page" 
                    @if(isset($web) && $web->design_config && isset($web->design_config['contactPage']) && $web->design_config['contactPage']) checked @endif> 
                {{ __('diseno.incluir_pagina_contacto') }}
            </label>
        </form>
        <br>
        
        <!-- Botones -->
        <div class="button-container">
            @if(isset($web))
                <button id="guardar-cambios" class="primary-button">Guardar Cambios</button>
                <button id="continuar" class="secondary-button">{{ __('diseno.continuar') }}</button>
            @else
                <button id="continuar" class="primary-button">{{ __('diseno.continuar') }}</button>
            @endif
        </div>

    </div>
    
</main>

@if(isset($web))
<!-- Modal de confirmación -->
<div id="save-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3 class="modal-title">Cambios guardados</h3>
        <p class="modal-message">La configuración se ha actualizado correctamente.</p>
        <div class="modal-actions">
            <button id="close-modal" class="modal-button confirm">Aceptar</button>
        </div>
    </div>
</div>
@endif
@stop

@push('scripts')
@vite(['resources/js/diseno.js'])
<script>
@if(isset($web))
// Modo edición - NO limpiar localStorage
// Datos existentes de la web para modo edición
window.webData = {
    id: {{ $web->id }},
    design_config: @json($web->design_config ?? []),
    isEditing: true,
    updateUrl: '{{ route("webs.update.design", $web) }}',
    editWelcomeUrl: '{{ route("webs.edit.welcome", $web) }}',
    editMainUrl: '{{ route("webs.edit.main", $web) }}',
    editContactUrl: '{{ route("webs.edit.contact", $web) }}',
    editUrl: '{{ route("webs.edit", $web) }}'
};

// Sobrescribir función proceedToNextStep para modo edición
function proceedToNextStep() {
    console.log("Navegando en modo edición");
    const welcomeMessage = document.getElementById('welcome-message').checked;
    const contactPage = document.getElementById('contact-page').checked;
    
    // Guardar configuración primero
    saveDesignConfig().then(() => {
        // En modo edición, navegar a las páginas de edición
        if (welcomeMessage) {
            window.location.href = window.webData.editWelcomeUrl;
        } else {
            window.location.href = window.webData.editMainUrl;
        }
    });
}

// Función para guardar cambios
function saveDesignConfig() {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        const designConfig = {
            welcomeMessage: document.getElementById('welcome-message').checked,
            contactPage: document.getElementById('contact-page').checked
        };
        
        formData.append('welcomeMessage', designConfig.welcomeMessage);
        formData.append('contactPage', designConfig.contactPage);
        
        fetch(window.webData.updateUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('save-modal').style.display = 'flex';
                resolve(data);
            } else {
                alert('Error al guardar los cambios');
                reject(new Error('Error al guardar'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar los cambios');
            reject(error);
        });
    });
}

// Event listeners
document.getElementById('guardar-cambios').addEventListener('click', () => {
    saveDesignConfig();
});
document.getElementById('continuar').addEventListener('click', proceedToNextStep);

// Cerrar modal
document.getElementById('close-modal').addEventListener('click', function() {
    document.getElementById('save-modal').style.display = 'none';
});

@else
// Modo creación normal - Limpiar localStorage SOLO aquí
localStorage.removeItem('mainPageData');
localStorage.removeItem('welcomeData');
localStorage.removeItem('contactData');
localStorage.removeItem('welcomeMessage');
localStorage.removeItem('contactPage');
console.log('localStorage limpiado para nuevo diseño');

window.webData = {
    isEditing: false
};

// IMPORTANTE: Sobrescribir proceedToNextStep para guardar configuración en localStorage
function proceedToNextStep() {
    const welcomeMessage = document.getElementById('welcome-message').checked;
    const contactPage = document.getElementById('contact-page').checked;
    
    // Guardar configuración en localStorage
    localStorage.setItem('welcomeMessage', welcomeMessage.toString());
    localStorage.setItem('contactPage', contactPage.toString());
    
    console.log('Configuración guardada:', {
        welcomeMessage: welcomeMessage,
        contactPage: contactPage
    });
    
    // Navegar según la configuración
    if (welcomeMessage) {
        window.location.href = '{{ route("bienvenida") }}';
    } else {
        window.location.href = '{{ route("principal") }}';
    }
}

// Event listener para continuar
document.getElementById('continuar').addEventListener('click', proceedToNextStep);
@endif
</script>
@endpush
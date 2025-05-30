@extends('layouts.app')
@section('title', isset($web) ? 'Editar Contacto - ' . $web->name : __('contacto.pagina_diseno_titulo'))
@push('styles')
    @vite(['resources/css/diseno.css'])
    @vite(['resources/css/contacto.css'])
@endpush
@section('content')
<div class="main-container">
    @if(isset($web))
        <!-- Navegación para edición -->
        <div class="edit-navigation">
            <a href="{{ route('webs.edit', $web) }}" class="back-link">← Volver a edición de web</a>
            <h2>Editando página de contacto: {{ $web->name }}</h2>
        </div>
    @endif

    <!-- Barra de progreso -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress" style="width: 80%;"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed">{{ __('contacto.inicio') }}</div>
            <div class="progress-step completed">{{ __('contacto.bienvenida') }}</div>
            <div class="progress-step completed">{{ __('contacto.principal') }}</div>
            <div class="progress-step active">{{ __('contacto.contacto') }}</div>
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
            <form id="contact-form">
                @csrf
                <div class="form-section">
                    <h3>{{ __('contacto.informacion_contacto') }}</h3>
                    <p>{{ __('contacto.selecciona_informacion') }}</p>
                    <!-- Las opciones de contacto se generarán dinámicamente por JavaScript -->
                    <div id="contact-options"></div>
                </div>

                <div class="form-section">
                    <h3>{{ __('contacto.mapa_ubicacion') }}</h3>
                    <div class="form-group">
                        <input type="checkbox" id="show-map" name="show_map"
                               @if(isset($web) && $web->contact_page_data && ($web->contact_page_data['show_map'] ?? false)) checked @endif>
                        <label for="show-map">{{ __('contacto.mostrar_mapa') }}</label>
                    </div>
                    <div class="form-group">
                        <label for="map-address">{{ __('contacto.direccion_mapa') }}</label>
                        <input type="text" id="map-address" name="map_address" class="form-control" 
                               placeholder="{{ __('contacto.placeholder_direccion') }}"
                               value="@if(isset($web) && $web->contact_page_data){{ $web->contact_page_data['map_address'] ?? '' }}@endif">
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
<script>
// Definir rutas para modo creación
@if(!isset($web))
window.routes = {
    publicar: '{{ route("publicar") }}'
};
@endif

@if(isset($web))
// Configuración para modo edición
window.webData = {
    id: {{ $web->id }},
    contact_page_data: @json(is_string($web->contact_page_data) ? json_decode($web->contact_page_data, true) : $web->contact_page_data ?? []),
    main_page_data: @json(is_string($web->main_page_data) ? json_decode($web->main_page_data, true) : $web->main_page_data ?? []),
    isEditing: true,
    updateUrl: '{{ route("webs.update.contact", $web) }}',
    editUrl: '{{ route("webs.edit", $web) }}',
    hasContactPage: true  // Añadir esta línea
};
@else
// Modo creación normal
window.webData = {
    isEditing: false
};
@endif
</script>
@vite(['resources/js/contacto.js'])
@endpush
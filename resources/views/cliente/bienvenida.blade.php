@extends('layouts.app')

@section('title', 'Página de Diseño - Zablo')

@push('styles')
    <!--CSS especifico de la pagina-->
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
                <div class="progress-step completed">Inicio</div>
                <div class="progress-step active">Bienvenida</div>
                <div class="progress-step">Principal</div>
                <div class="progress-step">Contacto</div>
                <div class="progress-step">Publicar</div>
            </div>
        </div>
        <div class="design-container">
        <!-- Contenedor de vista previa -->
        <div class="preview-container">
            <h3>Vista Previa</h3>
            <div id="preview" class="preview">
                <!-- Imagen de fondo -->
                <img class="background-image" id="background-image" src="" alt="Fondo" style="display: none;">
                <!-- Contenido -->
                <div class="content">
                    <!-- Aquí se mostrará el logo y el texto -->
                </div>
                <!-- Botón para entrar a la web -->
                <button id="enter-button">Entrar a la web</button>
            </div>
        </div>

        <!-- Contenedor del formulario -->
        <div class="form-container">
            <h3>Diseño de página de bienvenida</h3>
            <!-- Sección 1: Texto de Bienvenida -->
            <div class="form-section">
                <h3>Texto de Bienvenida</h3>
                <label for="welcome-title">Título:</label>
                <input type="text" id="welcome-title" placeholder="Título del mensaje">

                <label for="welcome-message">Mensaje:</label>
                <textarea id="welcome-message" placeholder="Escribe tu mensaje de bienvenida"></textarea>
            </div>
            <!-- Sección 6: Fuente -->
            <div class="form-section">
                <h3>Fuente</h3>
                <label for="font-family">Fuente del texto:</label>
                <select id="font-family">
                    <option value="Arial, sans-serif">Arial</option>
                    <option value="Georgia, serif">Georgia</option>
                    <option value="Courier New, monospace">Courier New</option>
                    <option value="Verdana, sans-serif">Verdana</option>
                    <option value="Times New Roman, serif">Times New Roman</option>
                </select>
            </div>
            <!-- Sección 2: Fondo -->
            <div class="form-section">
                <h3>Fondo</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="background-type" value="color" checked> Color de fondo
                    </label>
                    <label>
                        <input type="radio" name="background-type" value="image"> Imagen de fondo
                    </label>
                </div>

                <div id="color-background-controls">
                    <label for="welcome-bg-color">Color de fondo:</label>
                    <input type="color" id="welcome-bg-color" value="#ffffff">
                </div>

                <div id="image-background-controls" style="display: none;">
                    <label for="background-image-input">Imagen de fondo:</label>
                    <input type="file" id="background-image-input" accept="image/*">
                </div>
            </div>

            <!-- Sección 3: Logo -->
            <div class="form-section">
                <h3>Logo</h3>
                <label for="logo">Subir logotipo:</label>
                <input type="file" id="logo" accept="image/*">

                <label for="logo-size">Tamaño del logo (en píxeles):</label>
                <input type="number" id="logo-size" value="100" min="50" max="300">

                <div class="logo-position-container">
                    <label for="logo-position">Posición del logotipo:</label>
                    <select id="logo-position">
                        <option value="center">Centro</option>
                        <option value="left">Izquierda</option>
                        <option value="right">Derecha</option>
                    </select>
                </div>
            </div>

            <!-- Sección 4: Botón -->
            <div class="form-section">
                <h3>Botón</h3>
                <label for="button-text">Texto del botón:</label>
                <input type="text" id="button-text" placeholder="Texto del botón">

                <label for="button-color">Color del botón:</label>
                <input type="color" id="button-color" value="#0000ff">

                <label for="button-text-color">Color del texto del botón:</label>
                <input type="color" id="button-text-color" value="#ffffff">

                <label for="button-font-size">Tamaño del texto del botón:</label>
                <input type="number" id="button-font-size" value="16" min="10" max="50">

                <label for="button-padding">Tamaño del botón:</label>
                <input type="number" id="button-padding" value="10" min="5" max="30">
            </div>

            <!-- Sección 5: Contenido -->
            <div class="form-section">
                <h3>Contenido</h3>
                <label for="content-bg-color">Color de fondo del contenido:</label>
                <input type="color" id="content-bg-color" value="#ffffff">

                <label for="content-bg-opacity">Transparencia del fondo del contenido:</label>
                <input type="number" id="content-bg-opacity" value="80" min="0" max="100">

                <label for="content-text-color">Color del texto del contenido:</label>
                <input type="color" id="content-text-color" value="#000000">

                <label for="title-font-size">Tamaño del texto del título:</label>
                <input type="number" id="title-font-size" value="24" min="10" max="50">

                <label for="paragraph-font-size">Tamaño del texto del párrafo:</label>
                <input type="number" id="paragraph-font-size" value="16" min="10" max="50">

                <div class="text-style-container">
                    <label>
                        <input type="checkbox" id="title-bold"> Negrita (título)
                    </label>
                    <label>
                        <input type="checkbox" id="title-italic"> Cursiva (título)
                    </label>
                </div>

                <div class="text-style-container">
                    <label>
                        <input type="checkbox" id="paragraph-bold"> Negrita (párrafo)
                    </label>
                    <label>
                        <input type="checkbox" id="paragraph-italic"> Cursiva (párrafo)
                    </label>
                </div>
            </div>

        </div>
    </div>
    <div class="button-container">

        <button id="reset-button" class="reset-button">
            🗑️ Empezar de cero
        </button>
                <button id="continuar">Continuar</button>
        
        
        
            </div>
    </div>


<!-- Modal de confirmación -->
<div id="reset-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3 class="modal-title">¿Estás seguro?</h3>
        <p class="modal-message">Esta acción borrará todo tu diseño actual. ¿Deseas continuar?</p>
        
        <div class="modal-actions">
            <button id="cancel-reset" class="modal-button cancel">
                Cancelar
            </button>
            <button id="confirm-reset" class="modal-button confirm">
                Borrar todo
            </button>
        </div>
    </div>
</div>




@stop
@push('scripts')
    @vite(['resources/js/bienvenida.js'])
@endpush
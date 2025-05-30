function proceedToNextStep() {
    console.log('=== PROCEDIENDO AL SIGUIENTE PASO ===');
    
    const welcomeMessage = document.getElementById('welcome-message').checked;
    const contactPage = document.getElementById('contact-page').checked;
    const webName = document.getElementById('web-name').value.trim();
    
    console.log('Datos recolectados:', {
        welcomeMessage,
        contactPage,
        webName
    });
    
    // Validar que tenga nombre
    if (!webName) {
        alert('Por favor, introduce un nombre para tu web');
        document.getElementById('web-name').focus();
        return;
    }
    
    // Guardar configuración en localStorage (modo creación)
    localStorage.setItem('welcomeMessage', welcomeMessage.toString());
    localStorage.setItem('contactPage', contactPage.toString());
    localStorage.setItem('webName', webName);
    
    console.log('Configuración guardada en localStorage:', {
        webName: localStorage.getItem('webName'),
        welcomeMessage: localStorage.getItem('welcomeMessage'),
        contactPage: localStorage.getItem('contactPage')
    });
    
    // Navegar según la configuración
    if (welcomeMessage) {
        console.log('Navegando a página de bienvenida');
        window.location.href = '/diseno-bienvenida';
    } else {
        console.log('Navegando a página principal');
        window.location.href = '/diseno-principal';
    }
}

// Función para cargar datos existentes
function loadExistingData(data) {
    if (!data) return;
    
    console.log('Cargando datos existentes:', data);
    
    // Cargar configuración específica de diseño
    if (data.welcomeMessage !== undefined) {
        document.getElementById('welcome-message').checked = data.welcomeMessage;
    }
    if (data.contactPage !== undefined) {
        document.getElementById('contact-page').checked = data.contactPage;
    }
}

// Función para mostrar mensaje de éxito
function showSuccessMessage(message = 'Cambios guardados correctamente') {
    let modal = document.getElementById('success-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'success-modal';
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
            "></div>
            <div class="modal-content" style="
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 2rem;
                border-radius: 12px;
                max-width: 400px;
                width: 90%;
                z-index: 1000;
                text-align: center;
            ">
                <h3 style="color: #10b981; margin-bottom: 1rem;">✅ Éxito</h3>
                <p style="margin-bottom: 1.5rem;">${message}</p>
                <button id="close-success-modal" style="
                    background: #10b981;
                    color: white;
                    border: none;
                    padding: 0.75rem 1.5rem;
                    border-radius: 6px;
                    cursor: pointer;
                ">Aceptar</button>
            </div>
        `;
        modal.style.cssText = `
            display: flex;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
        `;
        
        document.body.appendChild(modal);
        
        document.getElementById('close-success-modal').addEventListener('click', () => {
            modal.style.display = 'none';
        });
    } else {
        modal.querySelector('p').textContent = message;
    }
    
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 3000);
}

// Función para guardar cambios (solo en modo edición)
function saveDesignConfig() {
    console.log('=== GUARDANDO CONFIGURACIÓN DE DISEÑO ===');
    
    if (!window.webData || !window.webData.isEditing) {
        console.error('No se detectó modo edición');
        return Promise.reject(new Error('No se detectó modo edición'));
    }
    
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        const csrfToken = document.querySelector('meta[name="csrf-token"]') || document.querySelector('input[name="_token"]');
        
        if (!csrfToken) {
            reject(new Error('Token CSRF no encontrado'));
            return;
        }
        
        formData.append('_token', csrfToken.content || csrfToken.value);
        
        const designConfig = {
            welcomeMessage: document.getElementById('welcome-message').checked,
            contactPage: document.getElementById('contact-page').checked
        };
        
        formData.append('welcomeMessage', designConfig.welcomeMessage);
        formData.append('contactPage', designConfig.contactPage);
        
        console.log('Enviando configuración:', designConfig);
        console.log('URL de actualización:', window.webData.updateUrl);
        
        fetch(window.webData.updateUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Respuesta del servidor:', response.status, response.statusText);
            return response.json();
        })
        .then(data => {
            console.log('Datos de respuesta:', data);
            if (data.success) {
                showSuccessMessage(data.message || 'Configuración guardada correctamente');
                resolve(data);
            } else {
                alert('Error al guardar los cambios: ' + (data.message || 'Error desconocido'));
                reject(new Error(data.message || 'Error al guardar'));
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            alert('Error al guardar los cambios');
            reject(error);
        });
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DISENO.JS CARGADO ===');
    
    const continueBtn = document.getElementById('continuar');
    const saveBtn = document.getElementById('guardar-cambios');
    
    console.log('Elementos encontrados:', {
        continueBtn: !!continueBtn,
        saveBtn: !!saveBtn,
        isEditing: window.webData && window.webData.isEditing
    });
    
    if (continueBtn) {
        continueBtn.addEventListener('click', proceedToNextStep);
        console.log('Event listener añadido al botón continuar');
    }
    
    // Solo en modo edición
    if (window.webData && window.webData.isEditing) {
        console.log('Modo edición detectado');
        loadExistingData(window.webData.design_config);
        
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                saveDesignConfig();
            });
            console.log('Event listener añadido al botón guardar cambios');
        }
        
        // Sobrescribir función para modo edición
        window.proceedToNextStep = function() {
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
            }).catch(error => {
                console.error('Error al guardar antes de continuar:', error);
            });
        };
    } else {
        console.log('Modo creación detectado');
        // En modo creación, limpiar localStorage para empezar fresco
        console.log('Limpiando localStorage para nueva web...');
        localStorage.removeItem('mainPageData');
        localStorage.removeItem('welcomeData');
        localStorage.removeItem('contactData');
        // NO limpiar welcomeMessage, contactPage, y webName porque los necesitamos
    }
    
    console.log('=== DISENO.JS INICIALIZADO ===');
});
// Elementos del DOM con verificaciones de seguridad
const welcomeTitleInput = document.getElementById('welcome-title');
const welcomeMessageInput = document.getElementById('welcome-message');
const welcomeBgColorInput = document.getElementById('welcome-bg-color');
const logoInput = document.getElementById('logo');
const logoSizeInput = document.getElementById('logo-size');
const logoPositionInput = document.getElementById('logo-position');
const backgroundImageInput = document.getElementById('background-image-input');
const preview = document.getElementById('preview');
const backgroundImageElement = document.getElementById('background-image');
const enterButton = document.getElementById('enter-button');
const contentContainer = preview ? preview.querySelector('.content') : null;
const colorBackgroundControls = document.getElementById('color-background-controls');
const imageBackgroundControls = document.getElementById('image-background-controls');
const backgroundTypeRadios = document.querySelectorAll('input[name="background-type"]');
const buttonTextInput = document.getElementById('button-text');
const buttonColorInput = document.getElementById('button-color');
const buttonTextColorInput = document.getElementById('button-text-color');
const buttonFontSizeInput = document.getElementById('button-font-size');
const buttonPaddingInput = document.getElementById('button-padding');
const contentBgColorInput = document.getElementById('content-bg-color');
const contentBgOpacityInput = document.getElementById('content-bg-opacity');
const contentTextColorInput = document.getElementById('content-text-color');
const titleFontSizeInput = document.getElementById('title-font-size');
const paragraphFontSizeInput = document.getElementById('paragraph-font-size');
const titleBoldInput = document.getElementById('title-bold');
const titleItalicInput = document.getElementById('title-italic');
const paragraphBoldInput = document.getElementById('paragraph-bold');
const paragraphItalicInput = document.getElementById('paragraph-italic');
const fontFamilyInput = document.getElementById('font-family');

// IndexedDB setup
let db;
const DB_NAME = 'WelcomePageDB';
const DB_VERSION = 1;
const STORE_NAME = 'images';

function initDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = (event) => {
            db = event.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'id' });
            }
        };

        request.onsuccess = (event) => {
            db = event.target.result;
            resolve(db);
        };

        request.onerror = (event) => {
            console.error('Error opening IndexedDB:', event.target.error);
            reject(event.target.error);
        };
    });
}

function saveImageToDB(id, file) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction([STORE_NAME], 'readwrite');
        const store = transaction.objectStore(STORE_NAME);
        
        const reader = new FileReader();
        reader.onload = (event) => {
            const request = store.put({
                id: id,
                data: event.target.result,
                type: file.type,
                name: file.name,
                lastModified: file.lastModified
            });

            request.onsuccess = () => resolve();
            request.onerror = (event) => reject(event.target.error);
        };
        reader.onerror = (event) => reject(event.target.error);
        reader.readAsDataURL(file);
    });
}

// Initialize DB when the page loads
initDB().catch(console.error);

// Store images when they are selected
if (logoInput) {
    logoInput.addEventListener('change', () => {
        const file = logoInput.files[0];
        if (file) {
            saveImageToDB('logoBienvenida', file).catch(console.error);
        }
    });
}

if (backgroundImageInput) {
    backgroundImageInput.addEventListener('change', () => {
        const file = backgroundImageInput.files[0];
        if (file) {
            saveImageToDB('background', file).catch(console.error);
        }
    });
}

// Función para actualizar la vista previa
function updatePreview() {
    if (!welcomeTitleInput || !contentContainer || !preview) {
        console.warn('Elementos DOM no encontrados para updatePreview');
        return;
    }
    
    const title = welcomeTitleInput.value;
    const message = welcomeMessageInput ? welcomeMessageInput.value : '';
    const bgColor = welcomeBgColorInput ? welcomeBgColorInput.value : '#ffffff';
    const logoFile = logoInput ? logoInput.files[0] : null;
    const logoSize = logoSizeInput ? logoSizeInput.value + 'px' : '100px';
    const logoPosition = logoPositionInput ? logoPositionInput.value : 'center';
    const backgroundImageFile = backgroundImageInput ? backgroundImageInput.files[0] : null;
    const backgroundTypeElement = document.querySelector('input[name="background-type"]:checked');
    const backgroundType = backgroundTypeElement ? backgroundTypeElement.value : 'color';
    const buttonText = buttonTextInput ? (buttonTextInput.value || "Entrar a la web") : "Entrar a la web";
    const buttonColor = buttonColorInput ? buttonColorInput.value : '#0000ff';
    const buttonTextColor = buttonTextColorInput ? buttonTextColorInput.value : '#ffffff';
    const buttonFontSize = buttonFontSizeInput ? buttonFontSizeInput.value + 'px' : '16px';
    const buttonPadding = buttonPaddingInput ? buttonPaddingInput.value + 'px' : '10px';
    const contentBgColor = contentBgColorInput ? contentBgColorInput.value : '#ffffff';
    const contentBgOpacity = contentBgOpacityInput ? contentBgOpacityInput.value / 100 : 0.8;
    const contentTextColor = contentTextColorInput ? contentTextColorInput.value : '#000000';
    const titleFontSize = titleFontSizeInput ? titleFontSizeInput.value + 'px' : '24px';
    const paragraphFontSize = paragraphFontSizeInput ? paragraphFontSizeInput.value + 'px' : '16px';
    const titleBold = titleBoldInput ? (titleBoldInput.checked ? 'bold' : 'normal') : 'normal';
    const titleItalic = titleItalicInput ? (titleItalicInput.checked ? 'italic' : 'normal') : 'normal';
    const paragraphBold = paragraphBoldInput ? (paragraphBoldInput.checked ? 'bold' : 'normal') : 'normal';
    const paragraphItalic = paragraphItalicInput ? (paragraphItalicInput.checked ? 'italic' : 'normal') : 'normal';
    const fontFamily = fontFamilyInput ? fontFamilyInput.value : 'Arial, sans-serif';

    // Limpiar el contenido previo
    contentContainer.innerHTML = '';

    // Crear un contenedor para el logo y el texto
    const logoTextContainer = document.createElement('div');
    logoTextContainer.style.display = 'flex';
    logoTextContainer.style.alignItems = 'center';
    logoTextContainer.style.gap = '20px';

    // Añadir el logo si existe
    if (logoFile) {
        const logo = document.createElement('img');
        logo.src = URL.createObjectURL(logoFile);
        logo.alt = 'Logotipo';
        logo.classList.add('logo');
        logo.style.width = logoSize;
        logoTextContainer.appendChild(logo);
    }

    // Crear un contenedor para el título y el párrafo
    const textContainer = document.createElement('div');
    textContainer.style.display = 'flex';
    textContainer.style.flexDirection = 'column';
    textContainer.style.gap = '10px';

    // Añadir el título y el mensaje
    if (title) {
        const titleElement = document.createElement('h2');
        titleElement.textContent = title;
        titleElement.style.color = contentTextColor;
        titleElement.style.fontSize = titleFontSize;
        titleElement.style.fontWeight = titleBold;
        titleElement.style.fontStyle = titleItalic;
        textContainer.appendChild(titleElement);
    }

    if (message) {
        const messageElement = document.createElement('p');
        messageElement.textContent = message;
        messageElement.style.color = contentTextColor;
        messageElement.style.fontSize = paragraphFontSize;
        messageElement.style.fontWeight = paragraphBold;
        messageElement.style.fontStyle = paragraphItalic;
        textContainer.appendChild(messageElement);
    }

    // Añadir el contenedor de texto al contenedor de logo y texto
    logoTextContainer.appendChild(textContainer);

    // Ajustar la disposición según la posición del logo
    if (logoPosition === 'center') {
        contentContainer.style.flexDirection = 'column';
        contentContainer.style.alignItems = 'center';
        contentContainer.style.textAlign = 'center';
        logoTextContainer.style.flexDirection = 'column';
        logoTextContainer.style.alignItems = 'center';
    } else if (logoPosition === 'left') {
        contentContainer.style.flexDirection = 'row';
        contentContainer.style.justifyContent = 'flex-start';
        contentContainer.style.textAlign = 'left';
        logoTextContainer.style.flexDirection = 'row';
    } else if (logoPosition === 'right') {
        contentContainer.style.flexDirection = 'row';
        contentContainer.style.justifyContent = 'flex-end';
        contentContainer.style.textAlign = 'right';
        logoTextContainer.style.flexDirection = 'row-reverse';
    }

    // Añadir el contenedor de logo y texto al contenido principal
    contentContainer.appendChild(logoTextContainer);

    // Añadir la imagen de fondo si se selecciona
    if (backgroundType === 'image' && backgroundImageFile && backgroundImageElement) {
        backgroundImageElement.src = URL.createObjectURL(backgroundImageFile);
        backgroundImageElement.style.display = 'block';
        preview.style.backgroundColor = 'transparent';
    } else {
        if (backgroundImageElement) {
            backgroundImageElement.style.display = 'none';
        }
        preview.style.backgroundColor = bgColor;
    }

    // Actualizar el botón
    if (enterButton) {
        enterButton.textContent = buttonText;
        enterButton.style.backgroundColor = buttonColor;
        enterButton.style.color = buttonTextColor;
        enterButton.style.fontSize = buttonFontSize;
        enterButton.style.padding = `${buttonPadding} ${buttonPadding * 2}px`;
        enterButton.style.display = 'block';
    }

    // Aplicar el color de fondo y texto del contenido
    contentContainer.style.backgroundColor = `rgba(${parseInt(contentBgColor.slice(1, 3), 16)}, ${parseInt(contentBgColor.slice(3, 5), 16)}, ${parseInt(contentBgColor.slice(5, 7), 16)}, ${contentBgOpacity})`;
    contentContainer.style.color = contentTextColor;

    // Aplicar la fuente seleccionada
    preview.style.fontFamily = fontFamily;
}

// Función para resetear el formulario y la vista previa
function resetForm() {
    if (welcomeTitleInput) welcomeTitleInput.value = '';
    if (welcomeMessageInput) welcomeMessageInput.value = '';
    if (welcomeBgColorInput) welcomeBgColorInput.value = '#ffffff';
    if (logoInput) logoInput.value = '';
    if (logoSizeInput) logoSizeInput.value = 100;
    if (logoPositionInput) logoPositionInput.value = 'center';
    if (backgroundImageInput) backgroundImageInput.value = '';
    if (buttonTextInput) buttonTextInput.value = '';
    if (buttonColorInput) buttonColorInput.value = '#0000ff';
    if (buttonTextColorInput) buttonTextColorInput.value = '#ffffff';
    if (buttonFontSizeInput) buttonFontSizeInput.value = 16;
    if (buttonPaddingInput) buttonPaddingInput.value = 10;
    if (contentBgColorInput) contentBgColorInput.value = '#ffffff';
    if (contentBgOpacityInput) contentBgOpacityInput.value = 80;
    if (contentTextColorInput) contentTextColorInput.value = '#000000';
    if (titleFontSizeInput) titleFontSizeInput.value = 24;
    if (paragraphFontSizeInput) paragraphFontSizeInput.value = 16;
    if (titleBoldInput) titleBoldInput.checked = false;
    if (titleItalicInput) titleItalicInput.checked = false;
    if (paragraphBoldInput) paragraphBoldInput.checked = false;
    if (paragraphItalicInput) paragraphItalicInput.checked = false;
    if (fontFamilyInput) fontFamilyInput.value = 'Arial, sans-serif';

    updatePreview();
}

// Función para guardar datos de bienvenida via AJAX
function saveWelcomeData() {
    return new Promise((resolve, reject) => {
        if (!window.webData || !window.webData.isEditing) {
            console.error('No se detectó modo edición');
            reject('No se detectó modo edición');
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        // Recopilar todos los datos del formulario
        formData.append('welcome_title', welcomeTitleInput ? welcomeTitleInput.value : '');
        formData.append('welcome_message', welcomeMessageInput ? welcomeMessageInput.value : '');
        formData.append('font_family', fontFamilyInput ? fontFamilyInput.value : 'Arial, sans-serif');
        const backgroundTypeElement = document.querySelector('input[name="background-type"]:checked');
        formData.append('background_type', backgroundTypeElement ? backgroundTypeElement.value : 'color');
        formData.append('background_color', welcomeBgColorInput ? welcomeBgColorInput.value : '#ffffff');
        formData.append('logo_size', logoSizeInput ? logoSizeInput.value : 100);
        formData.append('logo_position', logoPositionInput ? logoPositionInput.value : 'center');
        formData.append('button_text', buttonTextInput ? buttonTextInput.value : '');
        formData.append('button_color', buttonColorInput ? buttonColorInput.value : '#0000ff');
        formData.append('button_text_color', buttonTextColorInput ? buttonTextColorInput.value : '#ffffff');
        formData.append('button_font_size', buttonFontSizeInput ? buttonFontSizeInput.value : 16);
        formData.append('button_padding', buttonPaddingInput ? buttonPaddingInput.value : 10);
        formData.append('content_bg_color', contentBgColorInput ? contentBgColorInput.value : '#ffffff');
        formData.append('content_bg_opacity', contentBgOpacityInput ? contentBgOpacityInput.value : 80);
        formData.append('content_text_color', contentTextColorInput ? contentTextColorInput.value : '#000000');
        formData.append('title_font_size', titleFontSizeInput ? titleFontSizeInput.value : 24);
        formData.append('paragraph_font_size', paragraphFontSizeInput ? paragraphFontSizeInput.value : 16);
        formData.append('title_bold', titleBoldInput ? titleBoldInput.checked : false);
        formData.append('title_italic', titleItalicInput ? titleItalicInput.checked : false);
        formData.append('paragraph_bold', paragraphBoldInput ? paragraphBoldInput.checked : false);
        formData.append('paragraph_italic', paragraphItalicInput ? paragraphItalicInput.checked : false);
        
        // Añadir archivos si existen
        if (logoInput && logoInput.files[0]) {
            formData.append('logo', logoInput.files[0]);
        }
        if (backgroundImageInput && backgroundImageInput.files[0]) {
            formData.append('background_image', backgroundImageInput.files[0]);
        }
        
        fetch(window.webData.updateUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Página de bienvenida guardada correctamente');
                resolve(data);
            } else {
                alert('Error al guardar: ' + (data.message || 'Inténtalo de nuevo'));
                reject(data.message || 'Error al guardar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar los cambios');
            reject(error);
        });
    });
}

// Función para cargar datos existentes 
function loadExistingData(data) {
    if (!data) {
        console.log('❌ No hay datos para cargar');
        return;
    }
    
    console.log('📥 Iniciando carga de datos existentes:', data);
    
    // Cargar título - manejar ambos formatos
    const welcomeTitle = data.welcome_title || data.title;
    if (welcomeTitle && welcomeTitleInput) {
        console.log('✅ Cargando título:', welcomeTitle);
        welcomeTitleInput.value = welcomeTitle;
    } else {
        console.log('⚠️ Título no encontrado. welcome_title:', data.welcome_title, 'title:', data.title);
    }
    
    // Cargar mensaje - manejar ambos formatos
    const welcomeMessage = data.welcome_message || data.message;
    if (welcomeMessage && welcomeMessageInput) {
        console.log('✅ Cargando mensaje:', welcomeMessage);
        welcomeMessageInput.value = welcomeMessage;
    } else {
        console.log('⚠️ Mensaje no encontrado. welcome_message:', data.welcome_message, 'message:', data.message);
    }
    
    // Cargar fuente - manejar ambos formatos
    const fontFamily = data.font_family || data.fontFamily;
    if (fontFamily && fontFamilyInput) {
        console.log('✅ Cargando fuente:', fontFamily);
        fontFamilyInput.value = fontFamily;
    } else {
        console.log('⚠️ Fuente no encontrada. font_family:', data.font_family, 'fontFamily:', data.fontFamily);
    }
    
    // Cargar color de fondo - manejar ambos formatos
    const backgroundColor = data.background_color || data.bgColor;
    if (backgroundColor && welcomeBgColorInput) {
        console.log('✅ Cargando color de fondo:', backgroundColor);
        welcomeBgColorInput.value = backgroundColor;
    } else {
        console.log('⚠️ Color de fondo no encontrado. background_color:', data.background_color, 'bgColor:', data.bgColor);
    }
    
    // Cargar tamaño del logo - manejar ambos formatos
    const logoSize = data.logo_size || data.logoSize;
    if (logoSize && logoSizeInput) {
        console.log('✅ Cargando tamaño del logo:', logoSize);
        logoSizeInput.value = logoSize;
        const logoSizeValue = document.getElementById('logo-size-value');
        if (logoSizeValue) logoSizeValue.textContent = logoSize + 'px';
    }
    
    // Cargar posición del logo - manejar ambos formatos
    const logoPosition = data.logo_position || data.logoPosition;
    if (logoPosition && logoPositionInput) {
        console.log('✅ Cargando posición del logo:', logoPosition);
        logoPositionInput.value = logoPosition;
    }
    
    // Cargar texto del botón - manejar ambos formatos
    const buttonText = data.button_text || data.buttonText;
    if (buttonText && buttonTextInput) {
        console.log('✅ Cargando texto del botón:', buttonText);
        buttonTextInput.value = buttonText;
    }
    
    // Cargar color del botón - manejar ambos formatos
    const buttonColor = data.button_color || data.buttonColor;
    if (buttonColor && buttonColorInput) {
        console.log('✅ Cargando color del botón:', buttonColor);
        buttonColorInput.value = buttonColor;
    }
    
    // Cargar color del texto del botón - manejar ambos formatos
    const buttonTextColor = data.button_text_color || data.buttonTextColor;
    if (buttonTextColor && buttonTextColorInput) {
        console.log('✅ Cargando color del texto del botón:', buttonTextColor);
        buttonTextColorInput.value = buttonTextColor;
    }
    
    // Cargar tamaño de fuente del botón - manejar ambos formatos
    const buttonFontSize = data.button_font_size || data.buttonFontSize;
    if (buttonFontSize && buttonFontSizeInput) {
        console.log('✅ Cargando tamaño de fuente del botón:', buttonFontSize);
        buttonFontSizeInput.value = buttonFontSize;
        const buttonFontSizeValue = document.getElementById('button-font-size-value');
        if (buttonFontSizeValue) buttonFontSizeValue.textContent = buttonFontSize + 'px';
    }
    
    // Cargar padding del botón - manejar ambos formatos
    const buttonPadding = data.button_padding || data.buttonPadding;
    if (buttonPadding && buttonPaddingInput) {
        console.log('✅ Cargando padding del botón:', buttonPadding);
        buttonPaddingInput.value = buttonPadding;
        const buttonPaddingValue = document.getElementById('button-padding-value');
        if (buttonPaddingValue) buttonPaddingValue.textContent = buttonPadding + 'px';
    }
    
    // Cargar color de fondo del contenido - manejar ambos formatos
    const contentBgColor = data.content_bg_color || data.contentBgColor;
    if (contentBgColor && contentBgColorInput) {
        console.log('✅ Cargando color de fondo del contenido:', contentBgColor);
        contentBgColorInput.value = contentBgColor;
    }
    
    // Cargar opacidad del fondo del contenido - manejar ambos formatos
    const contentBgOpacity = data.content_bg_opacity || data.contentBgOpacity;
    if (contentBgOpacity && contentBgOpacityInput) {
        console.log('✅ Cargando opacidad del fondo del contenido:', contentBgOpacity);
        contentBgOpacityInput.value = contentBgOpacity;
        const contentBgOpacityValue = document.getElementById('content-bg-opacity-value');
        if (contentBgOpacityValue) contentBgOpacityValue.textContent = contentBgOpacity + '%';
    }
    
    // Cargar color del texto del contenido - manejar ambos formatos
    const contentTextColor = data.content_text_color || data.contentTextColor;
    if (contentTextColor && contentTextColorInput) {
        console.log('✅ Cargando color del texto del contenido:', contentTextColor);
        contentTextColorInput.value = contentTextColor;
    }
    
    // Cargar tamaño de fuente del título - manejar ambos formatos
    const titleFontSize = data.title_font_size || data.titleFontSize;
    if (titleFontSize && titleFontSizeInput) {
        console.log('✅ Cargando tamaño de fuente del título:', titleFontSize);
        titleFontSizeInput.value = titleFontSize;
        const titleFontSizeValue = document.getElementById('title-font-size-value');
        if (titleFontSizeValue) titleFontSizeValue.textContent = titleFontSize + 'px';
    }
    
    // Cargar tamaño de fuente del párrafo - manejar ambos formatos
    const paragraphFontSize = data.paragraph_font_size || data.paragraphFontSize;
    if (paragraphFontSize && paragraphFontSizeInput) {
        console.log('✅ Cargando tamaño de fuente del párrafo:', paragraphFontSize);
        paragraphFontSizeInput.value = paragraphFontSize;
        const paragraphFontSizeValue = document.getElementById('paragraph-font-size-value');
        if (paragraphFontSizeValue) paragraphFontSizeValue.textContent = paragraphFontSize + 'px';
    }
    
    // Cargar checkboxes - manejar ambos formatos
    const titleBold = data.title_bold !== undefined ? data.title_bold : data.titleBold;
    if (titleBold !== undefined && titleBoldInput) {
        console.log('✅ Cargando título en negrita:', titleBold);
        titleBoldInput.checked = titleBold === true || titleBold === 'true' || titleBold === 1;
    }
    
    const titleItalic = data.title_italic !== undefined ? data.title_italic : data.titleItalic;
    if (titleItalic !== undefined && titleItalicInput) {
        console.log('✅ Cargando título en cursiva:', titleItalic);
        titleItalicInput.checked = titleItalic === true || titleItalic === 'true' || titleItalic === 1;
    }
    
    const paragraphBold = data.paragraph_bold !== undefined ? data.paragraph_bold : data.paragraphBold;
    if (paragraphBold !== undefined && paragraphBoldInput) {
        console.log('✅ Cargando párrafo en negrita:', paragraphBold);
        paragraphBoldInput.checked = paragraphBold === true || paragraphBold === 'true' || paragraphBold === 1;
    }
    
    const paragraphItalic = data.paragraph_italic !== undefined ? data.paragraph_italic : data.paragraphItalic;
    if (paragraphItalic !== undefined && paragraphItalicInput) {
        console.log('✅ Cargando párrafo en cursiva:', paragraphItalic);
        paragraphItalicInput.checked = paragraphItalic === true || paragraphItalic === 'true' || paragraphItalic === 1;
    }
    
    // Manejar radio buttons de tipo de fondo - manejar ambos formatos
    const backgroundType = data.background_type || data.backgroundType;
    if (backgroundType) {
        console.log('✅ Cargando tipo de fondo:', backgroundType);
        const radioButton = document.querySelector(`input[name="background-type"][value="${backgroundType}"]`);
        if (radioButton) {
            radioButton.checked = true;
            // Mostrar/ocultar controles según el tipo
            if (colorBackgroundControls && imageBackgroundControls) {
                if (backgroundType === 'color') {
                    colorBackgroundControls.style.display = 'block';
                    imageBackgroundControls.style.display = 'none';
                } else {
                    colorBackgroundControls.style.display = 'none';
                    imageBackgroundControls.style.display = 'block';
                }
            }
        }
    }
    
    // 🖼️ CARGAR IMÁGENES - NUEVO
    console.log('🖼️ Iniciando carga de imágenes...');
    
    // Mostrar imágenes existentes en el DOM
    displayExistingImages(data);
    
    // Cargar archivos de imagen en los inputs (async)
    loadImagesFromServer(data).then(() => {
        console.log('✅ Imágenes cargadas completamente');
        updatePreview(); // Actualizar preview después de cargar imágenes
    }).catch(error => {
        console.error('❌ Error al cargar imágenes:', error);
        updatePreview(); // Actualizar preview aunque falle la carga de imágenes
    });
    
    console.log('📥 Finalizando carga de datos - actualizando preview inicial');
    updatePreview();
}

// Función para continuar al siguiente paso
function proceedToMainPage() {
    // MODO EDICIÓN: Guardar primero, luego navegar
    if (window.webData && window.webData.isEditing) {
        console.log("Guardando cambios antes de navegar en modo edición");
        
        // Deshabilitar el botón para evitar clics múltiples
        const continueBtn = document.getElementById('continuar');
        if (continueBtn) {
            const originalText = continueBtn.textContent;
            continueBtn.disabled = true;
            continueBtn.textContent = 'Guardando...';
            
            // Llamar a la función de guardar
            saveWelcomeData()
                .then(() => {
                    // Después de guardar exitosamente, navegar
                    continueBtn.textContent = 'Navegando...';
                    setTimeout(() => {
                        window.location.href = window.webData.editMainUrl;
                    }, 1500);
                })
                .catch((error) => {
                    console.error('Error al guardar:', error);
                    alert('Error al guardar los cambios. Por favor, inténtalo de nuevo.');
                    // Rehabilitar el botón
                    continueBtn.disabled = false;
                    continueBtn.textContent = originalText;
                });
        }
        
        return;
    }
    
    // MODO CREACIÓN NORMAL
    if (!window.routes || !window.routes.principal) {
        console.error('Rutas no configuradas para modo creación');
        alert('Error de configuración. Contacta al administrador.');
        return;
    }
    
    const welcomeData = {
        title: welcomeTitleInput ? welcomeTitleInput.value : '',
        message: welcomeMessageInput ? welcomeMessageInput.value : '',
        bgColor: welcomeBgColorInput ? welcomeBgColorInput.value : '#ffffff',
        logo: logoInput ? logoInput.files[0] : null,
        logoSize: logoSizeInput ? logoSizeInput.value : 100,
        logoPosition: logoPositionInput ? logoPositionInput.value : 'center',
        backgroundImage: backgroundImageInput ? backgroundImageInput.files[0] : null,
        backgroundType: document.querySelector('input[name="background-type"]:checked')?.value || 'color',
        buttonText: buttonTextInput ? buttonTextInput.value : '',
        buttonColor: buttonColorInput ? buttonColorInput.value : '#0000ff',
        buttonTextColor: buttonTextColorInput ? buttonTextColorInput.value : '#ffffff',
        buttonFontSize: buttonFontSizeInput ? buttonFontSizeInput.value : 16,
        buttonPadding: buttonPaddingInput ? buttonPaddingInput.value : 10,
        contentBgColor: contentBgColorInput ? contentBgColorInput.value : '#ffffff',
        contentBgOpacity: contentBgOpacityInput ? contentBgOpacityInput.value : 80,
        contentTextColor: contentTextColorInput ? contentTextColorInput.value : '#000000',
        titleFontSize: titleFontSizeInput ? titleFontSizeInput.value : 24,
        paragraphFontSize: paragraphFontSizeInput ? paragraphFontSizeInput.value : 16,
        titleBold: titleBoldInput ? titleBoldInput.checked : false,
        titleItalic: titleItalicInput ? titleItalicInput.checked : false,
        paragraphBold: paragraphBoldInput ? paragraphBoldInput.checked : false,
        paragraphItalic: paragraphItalicInput ? paragraphItalicInput.checked : false,
        fontFamily: fontFamilyInput ? fontFamilyInput.value : 'Arial, sans-serif',
    };

    localStorage.setItem('welcomeData', JSON.stringify(welcomeData));
    setTimeout(() => {
        window.location.href = window.routes.principal;
    }, 550);
}

// Función de debug para bienvenida
function debugWelcomeDataLoading() {
    console.log('=== DEBUG BIENVENIDA: Verificando carga de datos ===');
    
    if (window.webData) {
        console.log('window.webData existe:', window.webData);
        console.log('Modo edición:', window.webData.isEditing);
        
        if (window.webData.isEditing && window.webData.welcome_page_data) {
            console.log('Datos de bienvenida:', window.webData.welcome_page_data);
            console.log('welcome_title:', window.webData.welcome_page_data.welcome_title);
            console.log('title:', window.webData.welcome_page_data.title);
            console.log('welcome_message:', window.webData.welcome_page_data.welcome_message);
            console.log('message:', window.webData.welcome_page_data.message);
            console.log('background_color:', window.webData.welcome_page_data.background_color);
            console.log('bgColor:', window.webData.welcome_page_data.bgColor);
            console.log('font_family:', window.webData.welcome_page_data.font_family);
            console.log('fontFamily:', window.webData.welcome_page_data.fontFamily);
            
            // Verificar paths de imágenes
            console.log('🖼️ Verificando rutas de imágenes:');
            console.log('logo_path:', window.webData.welcome_page_data.logo_path);
            console.log('background_image_path:', window.webData.welcome_page_data.background_image_path);
        }
    } else {
        console.log('window.webData NO existe');
    }
    
    // Verificar elementos DOM
    console.log('=== Verificando elementos DOM ===');
    console.log('welcomeTitleInput:', welcomeTitleInput ? 'EXISTE' : 'NO EXISTE');
    if (welcomeTitleInput) console.log('Valor actual:', welcomeTitleInput.value);
    
    console.log('welcomeMessageInput:', welcomeMessageInput ? 'EXISTE' : 'NO EXISTE');
    if (welcomeMessageInput) console.log('Valor actual:', welcomeMessageInput.value);
    
    console.log('logoInput:', logoInput ? 'EXISTE' : 'NO EXISTE');
    if (logoInput) console.log('Archivos en logoInput:', logoInput.files.length);
    
    console.log('backgroundImageInput:', backgroundImageInput ? 'EXISTE' : 'NO EXISTE');
    if (backgroundImageInput) console.log('Archivos en backgroundImageInput:', backgroundImageInput.files.length);
    
    console.log('=== Fin del debug bienvenida ===');
}

// Event listeners principales
document.addEventListener('DOMContentLoaded', function() {
    // Leer configuración desde localStorage
    const welcomeMessage = localStorage.getItem('welcomeMessage') === 'true';
    const contactPage = localStorage.getItem('contactPage') === 'true';

    console.log('🟢 Configuración cargada:');
    console.log('¿Incluir mensaje de bienvenida?', welcomeMessage);
    console.log('¿Incluir página de contacto?', contactPage);

    // Configurar modo edición
    if (window.webData && window.webData.isEditing) {
        console.log('Modo edición detectado en bienvenida');
        console.log('Datos a cargar:', window.webData.welcome_page_data);
        
        // PRIMERO cargar los datos existentes
        loadExistingData(window.webData.welcome_page_data);
        
        // DESPUÉS ejecutar el debug
        setTimeout(() => {
            debugWelcomeDataLoading();
        }, 300);
        
        // Añadir botón guardar si no existe
        const existingSaveBtn = document.getElementById('guardar-cambios');
        if (existingSaveBtn) {
            existingSaveBtn.addEventListener('click', () => {
                saveWelcomeData().catch(error => {
                    console.error('Error al guardar:', error);
                });
            });
        }
    } else {
        // En modo creación normal, también ejecutar debug
        setTimeout(() => {
            debugWelcomeDataLoading();
        }, 200);
    }
    
    // Actualizar los valores mostrados en los ranges
    const rangeInputs = [
        { input: logoSizeInput, display: 'logo-size-value', suffix: 'px' },
        { input: buttonFontSizeInput, display: 'button-font-size-value', suffix: 'px' },
        { input: buttonPaddingInput, display: 'button-padding-value', suffix: 'px' },
        { input: contentBgOpacityInput, display: 'content-bg-opacity-value', suffix: '%' },
        { input: titleFontSizeInput, display: 'title-font-size-value', suffix: 'px' },
        { input: paragraphFontSizeInput, display: 'paragraph-font-size-value', suffix: 'px' }
    ];
    
    rangeInputs.forEach(({ input, display, suffix }) => {
        if (input) {
            input.addEventListener('input', function() {
                const displayElement = document.getElementById(display);
                if (displayElement) {
                    displayElement.textContent = this.value + suffix;
                }
                updatePreview();
            });
        }
    });
});

// Event listeners para actualizar la vista previa automáticamente - con verificaciones
if (welcomeTitleInput) welcomeTitleInput.addEventListener('input', updatePreview);
if (welcomeMessageInput) welcomeMessageInput.addEventListener('input', updatePreview);
if (welcomeBgColorInput) welcomeBgColorInput.addEventListener('input', updatePreview);
if (logoInput) logoInput.addEventListener('change', updatePreview);
if (logoSizeInput) logoSizeInput.addEventListener('input', updatePreview);
if (logoPositionInput) logoPositionInput.addEventListener('change', updatePreview);
if (backgroundImageInput) backgroundImageInput.addEventListener('change', updatePreview);
if (buttonTextInput) buttonTextInput.addEventListener('input', updatePreview);
if (buttonColorInput) buttonColorInput.addEventListener('input', updatePreview);
if (buttonTextColorInput) buttonTextColorInput.addEventListener('input', updatePreview);
if (buttonFontSizeInput) buttonFontSizeInput.addEventListener('input', updatePreview);
if (buttonPaddingInput) buttonPaddingInput.addEventListener('input', updatePreview);
if (contentBgColorInput) contentBgColorInput.addEventListener('input', updatePreview);
if (contentBgOpacityInput) contentBgOpacityInput.addEventListener('input', updatePreview);
if (contentTextColorInput) contentTextColorInput.addEventListener('input', updatePreview);
if (titleFontSizeInput) titleFontSizeInput.addEventListener('input', updatePreview);
if (paragraphFontSizeInput) paragraphFontSizeInput.addEventListener('input', updatePreview);
if (titleBoldInput) titleBoldInput.addEventListener('change', updatePreview);
if (titleItalicInput) titleItalicInput.addEventListener('change', updatePreview);
if (paragraphBoldInput) paragraphBoldInput.addEventListener('change', updatePreview);
if (paragraphItalicInput) paragraphItalicInput.addEventListener('change', updatePreview);
if (fontFamilyInput) fontFamilyInput.addEventListener('change', updatePreview);

// Cambiar entre color de fondo e imagen de fondo
if (backgroundTypeRadios && backgroundTypeRadios.length > 0) {
    backgroundTypeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (colorBackgroundControls && imageBackgroundControls) {
                if (radio.value === 'color') {
                    colorBackgroundControls.style.display = 'block';
                    imageBackgroundControls.style.display = 'none';
                } else {
                    colorBackgroundControls.style.display = 'none';
                    imageBackgroundControls.style.display = 'block';
                }
            }
            updatePreview();
        });
    });
}

// Modal de reset
const resetButton = document.getElementById('reset-button');
const resetModal = document.getElementById('reset-modal');
const cancelReset = document.getElementById('cancel-reset');
const confirmReset = document.getElementById('confirm-reset');

if (resetButton && resetModal) {
    resetButton.addEventListener('click', () => {
        resetModal.style.display = 'flex';
    });
}

if (cancelReset && resetModal) {
    cancelReset.addEventListener('click', () => {
        resetModal.style.display = 'none';
    });
}

if (resetModal) {
    const modalOverlay = resetModal.querySelector('.modal-overlay');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', () => {
            resetModal.style.display = 'none';
        });
    }
}

if (confirmReset) {
    confirmReset.addEventListener('click', () => {
        resetForm();
        if (resetModal) {
            resetModal.style.display = 'none';
        }
    });
}

// Event listener para el botón continuar
const continuarBtn = document.getElementById('continuar');
if (continuarBtn) {
    continuarBtn.addEventListener('click', proceedToMainPage);
}

// Función para cargar imágenes desde el servidor en modo edición
async function loadImagesFromServer(data) {
    if (!window.webData || !window.webData.isEditing) {
        return; // Solo en modo edición
    }
    
    console.log('🖼️ Cargando imágenes desde el servidor (bienvenida)...');
    console.log('🔍 Buscando campos de imágenes en:', Object.keys(data));
    
    try {
        // Buscar el logo con diferentes nombres posibles
        const logoFields = ['logo_path', 'logo_url', 'logo_file', 'logo', 'logoPath', 'logoUrl'];
        let logoPath = null;
        
        for (const field of logoFields) {
            if (data[field]) {
                logoPath = data[field];
                console.log(`📸 Logo encontrado en campo "${field}":`, logoPath);
                break;
            }
        }
        
        // Cargar logo si existe
        if (logoPath && logoInput) {
            try {
                //Construir URL correcta
                const fullLogoPath = logoPath.startsWith('http') ? logoPath : 
                                   logoPath.startsWith('/') ? logoPath :
                                   '/storage/' + logoPath;
                
                console.log('📸 Cargando logo desde URL completa:', fullLogoPath);
                
                const logoResponse = await fetch(fullLogoPath);
                if (logoResponse.ok) {
                    const logoBlob = await logoResponse.blob();
                    const logoFile = new File([logoBlob], 'logo.jpg', { type: logoBlob.type });
                    
                    const logoDataTransfer = new DataTransfer();
                    logoDataTransfer.items.add(logoFile);
                    logoInput.files = logoDataTransfer.files;
                    
                    console.log('✅ Logo cargado correctamente');
                } else {
                    console.log('⚠️ No se pudo cargar el logo desde:', fullLogoPath, 'Status:', logoResponse.status);
                }
            } catch (error) {
                console.log('⚠️ Error al cargar logo:', error);
            }
        }
        
        // Buscar imagen de fondo
        const bgFields = ['background_image_path', 'background_image_url', 'background_image', 'backgroundImagePath'];
        let bgPath = null;
        
        for (const field of bgFields) {
            if (data[field]) {
                bgPath = data[field];
                console.log(`📸 Imagen de fondo encontrada en campo "${field}":`, bgPath);
                break;
            }
        }
        
        // Cargar imagen de fondo si existe
        if (bgPath && backgroundImageInput) {
            try {
                //Construir URL correcta
                const fullBgPath = bgPath.startsWith('http') ? bgPath : 
                                  bgPath.startsWith('/') ? bgPath :
                                  '/storage/' + bgPath;
                
                console.log('📸 Cargando imagen de fondo desde URL completa:', fullBgPath);
                
                const bgResponse = await fetch(fullBgPath);
                if (bgResponse.ok) {
                    const bgBlob = await bgResponse.blob();
                    const bgFile = new File([bgBlob], 'background.jpg', { type: bgBlob.type });
                    
                    const bgDataTransfer = new DataTransfer();
                    bgDataTransfer.items.add(bgFile);
                    backgroundImageInput.files = bgDataTransfer.files;
                    
                    // También mostrar la imagen de fondo en el preview
                    if (backgroundImageElement) {
                        backgroundImageElement.src = fullBgPath;
                        backgroundImageElement.style.display = 'block';
                    }
                    
                    console.log('✅ Imagen de fondo cargada correctamente');
                } else {
                    console.log('⚠️ No se pudo cargar la imagen de fondo desde:', fullBgPath, 'Status:', bgResponse.status);
                }
            } catch (error) {
                console.log('⚠️ Error al cargar imagen de fondo:', error);
            }
        }
        
    } catch (error) {
        console.error('❌ Error general al cargar imágenes en bienvenida:', error);
    }
}

// Función para verificar y mostrar imágenes existentes en el DOM
function displayExistingImages(data) {
    // Mostrar imagen de fondo si existe la ruta
    if (data.background_image_path && backgroundImageElement) {
        console.log('🖼️ Mostrando imagen de fondo existente:', data.background_image_path);
        backgroundImageElement.src = data.background_image_path;
        backgroundImageElement.style.display = 'block';
        
        // Cambiar el tipo de fondo a imagen si hay una imagen
        const imageRadio = document.querySelector('input[name="background-type"][value="image"]');
        if (imageRadio) {
            imageRadio.checked = true;
            if (colorBackgroundControls && imageBackgroundControls) {
                colorBackgroundControls.style.display = 'none';
                imageBackgroundControls.style.display = 'block';
            }
        }
    }
}

// Inicializar la vista previa
updatePreview();
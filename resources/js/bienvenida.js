document.addEventListener('DOMContentLoaded', () => {
    // Leer desde localStorage y convertir a booleanos
    const welcomeMessage = localStorage.getItem('welcomeMessage') === 'true';
    const contactPage = localStorage.getItem('contactPage') === 'true';

    console.log('🟢 Configuración cargada:');
    console.log('¿Incluir mensaje de bienvenida?', welcomeMessage);
    console.log('¿Incluir página de contacto?', contactPage);

});


// Elementos del DOM
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
const contentContainer = preview.querySelector('.content');
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
logoInput.addEventListener('change', () => {
    const file = logoInput.files[0];
    if (file) {
        saveImageToDB('logoBienvenida', file).catch(console.error);
    }
});

backgroundImageInput.addEventListener('change', () => {
    const file = backgroundImageInput.files[0];
    if (file) {
        saveImageToDB('background', file).catch(console.error);
    }
});

// Función para actualizar la vista previa (EXACTLY AS BEFORE)
function updatePreview() {
    const title = welcomeTitleInput.value;
    const message = welcomeMessageInput.value;
    const bgColor = welcomeBgColorInput.value;
    const logoFile = logoInput.files[0];
    const logoSize = logoSizeInput.value + 'px';
    const logoPosition = logoPositionInput.value;
    const backgroundImageFile = backgroundImageInput.files[0];
    const backgroundType = document.querySelector('input[name="background-type"]:checked').value;
    const buttonText = buttonTextInput.value || "Entrar a la web";
    const buttonColor = buttonColorInput.value;
    const buttonTextColor = buttonTextColorInput.value;
    const buttonFontSize = buttonFontSizeInput.value + 'px';
    const buttonPadding = buttonPaddingInput.value + 'px';
    const contentBgColor = contentBgColorInput.value;
    const contentBgOpacity = contentBgOpacityInput.value / 100;
    const contentTextColor = contentTextColorInput.value;
    const titleFontSize = titleFontSizeInput.value + 'px';
    const paragraphFontSize = paragraphFontSizeInput.value + 'px';
    const titleBold = titleBoldInput.checked ? 'bold' : 'normal';
    const titleItalic = titleItalicInput.checked ? 'italic' : 'normal';
    const paragraphBold = paragraphBoldInput.checked ? 'bold' : 'normal';
    const paragraphItalic = paragraphItalicInput.checked ? 'italic' : 'normal';
    const fontFamily = fontFamilyInput.value;

    // Limpiar el contenido previo
    contentContainer.innerHTML = '';

    // Crear un contenedor para el logo y el texto
    const logoTextContainer = document.createElement('div');
    logoTextContainer.style.display = 'flex';
    logoTextContainer.style.alignItems = 'center';
    logoTextContainer.style.gap = '20px'; // Espacio entre el logo y el texto

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
    textContainer.style.gap = '10px'; // Espacio entre el título y el párrafo

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
        logoTextContainer.style.flexDirection = 'row-reverse'; // Invertir el orden
    }

    // Añadir el contenedor de logo y texto al contenido principal
    contentContainer.appendChild(logoTextContainer);

    // Añadir la imagen de fondo si se selecciona
    if (backgroundType === 'image' && backgroundImageFile) {
        backgroundImageElement.src = URL.createObjectURL(backgroundImageFile);
        backgroundImageElement.style.display = 'block';
        preview.style.backgroundColor = 'transparent';
    } else {
        backgroundImageElement.style.display = 'none';
        preview.style.backgroundColor = bgColor;
    }

    // Actualizar el botón
    enterButton.textContent = buttonText;
    enterButton.style.backgroundColor = buttonColor;
    enterButton.style.color = buttonTextColor;
    enterButton.style.fontSize = buttonFontSize;
    enterButton.style.padding = `${buttonPadding} ${buttonPadding * 2}px`;

    // Aplicar el color de fondo y texto del contenido
    contentContainer.style.backgroundColor = `rgba(${parseInt(contentBgColor.slice(1, 3), 16)}, ${parseInt(contentBgColor.slice(3, 5), 16)}, ${parseInt(contentBgColor.slice(5, 7), 16)}, ${contentBgOpacity})`;
    contentContainer.style.color = contentTextColor;

    // Aplicar la fuente seleccionada
    preview.style.fontFamily = fontFamily;

    // Mostrar el botón "Entrar a la web"
    enterButton.style.display = 'block';
}

// Función para resetear el formulario y la vista previa
function resetForm() {
    welcomeTitleInput.value = '';
    welcomeMessageInput.value = '';
    welcomeBgColorInput.value = '#ffffff';
    logoInput.value = '';
    logoSizeInput.value = 100;
    logoPositionInput.value = 'center';
    backgroundImageInput.value = '';
    buttonTextInput.value = '';
    buttonColorInput.value = '#0000ff';
    buttonTextColorInput.value = '#ffffff';
    buttonFontSizeInput.value = 16;
    buttonPaddingInput.value = 10;
    contentBgColorInput.value = '#ffffff';
    contentBgOpacityInput.value = 80;
    contentTextColorInput.value = '#000000';
    titleFontSizeInput.value = 24;
    paragraphFontSizeInput.value = 16;
    titleBoldInput.checked = false;
    titleItalicInput.checked = false;
    paragraphBoldInput.checked = false;
    paragraphItalicInput.checked = false;
    fontFamilyInput.value = 'Arial, sans-serif';

    // Actualizar la vista previa
    updatePreview();
}

// Actualizar la vista previa automáticamente
welcomeTitleInput.addEventListener('input', updatePreview);
welcomeMessageInput.addEventListener('input', updatePreview);
welcomeBgColorInput.addEventListener('input', updatePreview);
logoInput.addEventListener('change', updatePreview);
logoSizeInput.addEventListener('input', updatePreview);
logoPositionInput.addEventListener('change', updatePreview);
backgroundImageInput.addEventListener('change', updatePreview);
buttonTextInput.addEventListener('input', updatePreview);
buttonColorInput.addEventListener('input', updatePreview);
buttonTextColorInput.addEventListener('input', updatePreview);
buttonFontSizeInput.addEventListener('input', updatePreview);
buttonPaddingInput.addEventListener('input', updatePreview);
contentBgColorInput.addEventListener('input', updatePreview);
contentBgOpacityInput.addEventListener('input', updatePreview);
contentTextColorInput.addEventListener('input', updatePreview);
titleFontSizeInput.addEventListener('input', updatePreview);
paragraphFontSizeInput.addEventListener('input', updatePreview);
titleBoldInput.addEventListener('change', updatePreview);
titleItalicInput.addEventListener('change', updatePreview);
paragraphBoldInput.addEventListener('change', updatePreview);
paragraphItalicInput.addEventListener('change', updatePreview);
fontFamilyInput.addEventListener('change', updatePreview);

// Cambiar entre color de fondo e imagen de fondo
backgroundTypeRadios.forEach(radio => {
    radio.addEventListener('change', () => {
        if (radio.value === 'color') {
            colorBackgroundControls.style.display = 'block';
            imageBackgroundControls.style.display = 'none';
        } else {
            colorBackgroundControls.style.display = 'none';
            imageBackgroundControls.style.display = 'block';
        }
        updatePreview();
    });
});

// Función para continuar al siguiente paso
function proceedToMainPage() {
    const welcomeData = {
        title: welcomeTitleInput.value,
        message: welcomeMessageInput.value,
        bgColor: welcomeBgColorInput.value,
        logo: logoInput.files[0],
        logoSize: logoSizeInput.value,
        logoPosition: logoPositionInput.value,
        backgroundImage: backgroundImageInput.files[0],
        backgroundType: document.querySelector('input[name="background-type"]:checked').value,
        buttonText: buttonTextInput.value,
        buttonColor: buttonColorInput.value,
        buttonTextColor: buttonTextColorInput.value,
        buttonFontSize: buttonFontSizeInput.value,
        buttonPadding: buttonPaddingInput.value,
        contentBgColor: contentBgColorInput.value,
        contentBgOpacity: contentBgOpacityInput.value,
        contentTextColor: contentTextColorInput.value,
        titleFontSize: titleFontSizeInput.value,
        paragraphFontSize: paragraphFontSizeInput.value,
        titleBold: titleBoldInput.checked,
        titleItalic: titleItalicInput.checked,
        paragraphBold: paragraphBoldInput.checked,
        paragraphItalic: paragraphItalicInput.checked,
        fontFamily: fontFamilyInput.value,
    };

    // Guardar los datos en localStorage
    localStorage.setItem('welcomeData', JSON.stringify(welcomeData));

    // Redirigir al siguiente paso
        setTimeout(() => {
            window.location.href = window.routes.principal;
    
        }, 550);
  
}

// Modal de reset
const resetButton = document.getElementById('reset-button');
const resetModal = document.getElementById('reset-modal');
const cancelReset = document.getElementById('cancel-reset');
const confirmReset = document.getElementById('confirm-reset');

// Abrir modal
resetButton.addEventListener('click', () => {
    resetModal.style.display = 'flex';
});

// Cerrar modal al hacer clic en cancelar o en el overlay
cancelReset.addEventListener('click', () => {
    resetModal.style.display = 'none';
});

resetModal.querySelector('.modal-overlay').addEventListener('click', () => {
    resetModal.style.display = 'none';
});

// Confirmar reset
confirmReset.addEventListener('click', () => {
    resetForm();
    resetModal.style.display = 'none';
});

// Inicializar la vista previa
updatePreview();

document.getElementById('continuar').addEventListener('click', proceedToMainPage);

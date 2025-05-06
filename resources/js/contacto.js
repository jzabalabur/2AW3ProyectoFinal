// Elementos del DOM
const preview = document.getElementById('preview');
const resetBtn = document.getElementById('reset-btn');
const continueBtn = document.getElementById('continue-btn');
const contactOptions = document.getElementById('contact-options');
const showMapCheckbox = document.getElementById('show-map');
const mapAddressInput = document.getElementById('map-address');

// Variables para IndexedDB
let db;
const DB_NAME = 'WebDesignDB';
const DB_VERSION = 1;
const STORE_NAME = 'images';

// Opciones de contacto
const contactCheckboxes = [
    { id: 'phone', title: 'Teléfono' },
    { id: 'email', title: 'Email' },
    { id: 'address', title: 'Dirección' },
    { id: 'schedule', title: 'Horario' },
    { id: 'social', title: 'Redes Sociales' }
];

// Inicializar opciones de contacto
function initContactOptions() {
    contactOptions.innerHTML = '';
    
    contactCheckboxes.forEach(option => {
        const optionContainer = document.createElement('div');
        optionContainer.className = 'contact-option';
        optionContainer.style.display = 'flex';
        optionContainer.style.alignItems = 'center';
        optionContainer.style.marginBottom = '15px';
        optionContainer.style.gap = '10px';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = option.id;
        checkbox.name = option.id;
        
        const label = document.createElement('label');
        label.htmlFor = option.id;
        label.textContent = option.title;
        label.style.fontWeight = 'bold';
        label.style.whiteSpace = 'nowrap';
        
        const input = document.createElement('input');
        input.type = 'text';
        input.id = `${option.id}-text`;
        input.placeholder = `Introduce tu ${option.title.toLowerCase()}`;
        input.style.padding = '8px';
        input.style.borderRadius = '4px';
        input.style.border = '1px solid #ccc';
        input.style.flex = '1';
        input.style.minWidth = '0';
        
        optionContainer.appendChild(checkbox);
        optionContainer.appendChild(label);
        optionContainer.appendChild(input);
        contactOptions.appendChild(optionContainer);
        
        checkbox.addEventListener('change', updatePreview);
        input.addEventListener('input', updatePreview);
    });

    showMapCheckbox.addEventListener('change', updatePreview);
    mapAddressInput.addEventListener('input', updatePreview);
}

// Función para aclarar un color
function lightenColor(color, percent) {
    const num = parseInt(color.replace('#', ''), 16);
    const amt = Math.round(2.55 * percent);
    const R = (num >> 16) + amt;
    const G = (num >> 8 & 0x00FF) + amt;
    const B = (num & 0x0000FF) + amt;
    
    return `#${(
        0x1000000 +
        (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 +
        (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 +
        (B < 255 ? (B < 1 ? 0 : B) : 255)
    ).toString(16).slice(1)}`;
}

// Función para actualizar la vista previa
async function updatePreview() {
    try {
        const mainPageData = JSON.parse(localStorage.getItem('mainPageData') || '{}');
        
        const newPage = document.createElement('div');
        newPage.style.display = 'flex';
        newPage.style.flexDirection = 'column';
        newPage.style.minHeight = '100vh';
        newPage.style.backgroundColor = mainPageData.bgColor || '#ffffff';
        newPage.style.color = mainPageData.textColor || '#000000';
        newPage.style.fontFamily = mainPageData.fontFamily || 'Arial, sans-serif';

        // Header
        const previewHeader = document.createElement('header');
        previewHeader.style.backgroundColor = mainPageData.header?.bgColor || '#f8f8f8';
        previewHeader.style.padding = mainPageData.header?.padding || '15px';
        previewHeader.style.display = 'flex';
        previewHeader.style.alignItems = 'center';
        
        const headerContainer = document.createElement('div');
        headerContainer.style.display = 'flex';
        headerContainer.style.alignItems = 'center';
        headerContainer.style.gap = '20px';
        headerContainer.style.width = '100%';

        const logoPosition = mainPageData.logo?.position || 'center';
        
        if (logoPosition === 'left') {
            headerContainer.style.flexDirection = 'row';
            headerContainer.style.justifyContent = 'space-between';
            previewHeader.style.paddingLeft = mainPageData.header?.paddingLeft || '30px';
            previewHeader.style.paddingRight = mainPageData.header?.paddingRight || '30px';
        } else {
            headerContainer.style.flexDirection = 'column';
            headerContainer.style.justifyContent = 'center';
            headerContainer.style.alignItems = 'center';
        }

        if (mainPageData.logo?.id) {
            const logoImage = await getImageFromDB(mainPageData.logo.id);
            if (logoImage) {
                const logo = document.createElement('img');
                logo.src = logoImage.data;
                logo.alt = 'Logotipo';
                logo.style.maxHeight = '60px';
                logo.style.width = 'auto';
                logo.style.objectFit = 'contain';
                headerContainer.appendChild(logo);
            }
        }

        if (mainPageData.header?.text) {
            const headerText = document.createElement('div');
            headerText.textContent = mainPageData.header.text;
            headerText.style.color = mainPageData.header.textColor || '#000000';
            headerText.style.fontFamily = mainPageData.fontFamily || 'Arial, sans-serif';
            headerText.style.fontWeight = 'bold';
            
            if (logoPosition === 'left') {
                headerText.style.textAlign = 'right';
                headerText.style.paddingRight = '20px';
            } else {
                headerText.style.textAlign = 'center';
            }
            
            headerContainer.appendChild(headerText);
        }

        previewHeader.appendChild(headerContainer);
        newPage.appendChild(previewHeader);

        // Navbar
        if (localStorage.getItem('contactPage') === 'true') {
            const navBar = document.createElement('nav');
            navBar.style.backgroundColor = lightenColor(mainPageData.header?.bgColor || '#f8f8f8', 20);
            navBar.style.padding = '0px';
            navBar.style.display = 'flex';
            navBar.style.justifyContent = 'center';
            navBar.style.gap = '10px';
            navBar.style.borderBottom = '1px solid rgba(0,0,0,0.1)';

            const homeButton = document.createElement('button');
            homeButton.textContent = 'Inicio';
            homeButton.style.padding = '8px 16px';
            homeButton.style.border = 'none';
            homeButton.style.background = 'none';
            homeButton.style.color = mainPageData.textColor || '#000000';
            homeButton.style.cursor = 'pointer';
            homeButton.style.fontFamily = mainPageData.fontFamily || 'Arial, sans-serif';
            homeButton.style.fontSize = "12px";
            homeButton.style.borderRadius = '4px';
            homeButton.style.transition = 'background-color 0.3s ease';
            
            homeButton.addEventListener('mouseover', () => {
                homeButton.style.backgroundColor = 'rgba(0, 0, 0, 0.1)';
            });
            
            homeButton.addEventListener('mouseout', () => {
                homeButton.style.backgroundColor = 'transparent';
            });

            const contactButton = document.createElement('button');
            contactButton.textContent = 'Contacto';
            contactButton.style.padding = '8px 16px';
            contactButton.style.border = 'none';
            contactButton.style.background = 'none';
            contactButton.style.color = mainPageData.textColor || '#000000';
            contactButton.style.cursor = 'pointer';
            contactButton.style.fontFamily = mainPageData.fontFamily || 'Arial, sans-serif';
            contactButton.style.fontSize = "12px";
            contactButton.style.borderRadius = '4px';
            contactButton.style.transition = 'background-color 0.3s ease';
            
            contactButton.addEventListener('mouseover', () => {
                contactButton.style.backgroundColor = 'rgba(0, 0, 0, 0.1)';
            });
            
            contactButton.addEventListener('mouseout', () => {
                contactButton.style.backgroundColor = 'transparent';
            });

            navBar.appendChild(homeButton);
            navBar.appendChild(contactButton);
            newPage.appendChild(navBar);
        }

        // Contenido principal
        const mainContent = document.createElement('div');
        mainContent.style.flex = '1';
        mainContent.style.padding = '20px';
        mainContent.style.display = 'flex';
        mainContent.style.flexDirection = 'column';
        mainContent.style.alignItems = 'center';
        
        // Contenedor para datos de contacto
        const contactDataContainer = document.createElement('div');
        contactDataContainer.style.width = '100%';
        contactDataContainer.style.maxWidth = '1000px';
        contactDataContainer.style.marginBottom = '30px';
        
        // Información de contacto
        const contactInfoContainer = document.createElement('div');
        contactInfoContainer.style.width = '100%';
        
        contactCheckboxes.forEach(option => {
            const checkbox = document.getElementById(option.id);
            const input = document.getElementById(`${option.id}-text`);
            
            if (checkbox && checkbox.checked && input && input.value.trim()) {
                const contactItem = document.createElement('div');
                contactItem.style.marginBottom = '15px';
                
                const title = document.createElement('span');
                title.textContent = `${option.title}: `;
                title.style.fontWeight = 'bold';
                title.style.color = mainPageData.textColor || '#000000';
                
                const text = document.createElement('span');
                text.textContent = input.value.trim();
                text.style.color = mainPageData.textColor || '#000000';
                
                contactItem.appendChild(title);
                contactItem.appendChild(text);
                contactInfoContainer.appendChild(contactItem);
            }
        });
        
        if (contactInfoContainer.children.length === 0) {
            const placeholder = document.createElement('p');
            contactInfoContainer.appendChild(placeholder);
        }
        
        contactDataContainer.appendChild(contactInfoContainer);
        mainContent.appendChild(contactDataContainer);
        
        // Mapa (debajo de la información de contacto)
        if (showMapCheckbox.checked && mapAddressInput.value.trim()) {
            const mapContainer = document.createElement('div');
            mapContainer.style.width = '100%';
            mapContainer.style.maxWidth = '1000px';
            mapContainer.style.height = '300px';
            mapContainer.style.borderRadius = '8px';
            mapContainer.style.overflow = 'hidden';
            mapContainer.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
            mapContainer.style.marginBottom = '30px';
            
            const mapIframe = document.createElement('iframe');
            mapIframe.src = `https://maps.google.com/maps?q=${encodeURIComponent(mapAddressInput.value.trim())}&output=embed`;
            mapIframe.style.width = '100%';
            mapIframe.style.height = '100%';
            mapIframe.setAttribute('frameborder', '0');
            mapIframe.setAttribute('allowfullscreen', '');
            
            mapContainer.appendChild(mapIframe);
            mainContent.appendChild(mapContainer);
        }
        
        // Formulario de contacto (debajo del mapa)
        const contactForm = document.createElement('form');
        contactForm.style.width = '100%';
        contactForm.style.maxWidth = '1000px';
        contactForm.style.padding = '20px';
        contactForm.style.backgroundColor = '#f8f9fa';
        contactForm.style.borderRadius = '8px';
        contactForm.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
        
        const formTitle = document.createElement('h3');
        formTitle.textContent = 'Formulario de Contacto';
        formTitle.style.color = mainPageData.textColor || '#000000';
        formTitle.style.marginBottom = '20px';
        contactForm.appendChild(formTitle);
        
        // Campo Email
        const emailGroup = document.createElement('div');
        emailGroup.style.marginBottom = '15px';
        
        const emailLabel = document.createElement('label');
        emailLabel.textContent = 'Correo electrónico:';
        emailLabel.style.display = 'block';
        emailLabel.style.marginBottom = '5px';
        emailLabel.style.color = mainPageData.textColor || '#000000';
        
        const emailInput = document.createElement('input');
        emailInput.type = 'email';
        emailInput.required = true;
        emailInput.style.width = '100%';
        emailInput.style.padding = '8px';
        emailInput.style.borderRadius = '4px';
        emailInput.style.border = '1px solid #ccc';
        
        emailGroup.appendChild(emailLabel);
        emailGroup.appendChild(emailInput);
        contactForm.appendChild(emailGroup);
        
        // Campo Asunto
        const subjectGroup = document.createElement('div');
        subjectGroup.style.marginBottom = '15px';
        
        const subjectLabel = document.createElement('label');
        subjectLabel.textContent = 'Asunto:';
        subjectLabel.style.display = 'block';
        subjectLabel.style.marginBottom = '5px';
        subjectLabel.style.color = mainPageData.textColor || '#000000';
        
        const subjectInput = document.createElement('input');
        subjectInput.type = 'text';
        subjectInput.required = true;
        subjectInput.style.width = '100%';
        subjectInput.style.padding = '8px';
        subjectInput.style.borderRadius = '4px';
        subjectInput.style.border = '1px solid #ccc';
        
        subjectGroup.appendChild(subjectLabel);
        subjectGroup.appendChild(subjectInput);
        contactForm.appendChild(subjectGroup);
        
        // Campo Mensaje
        const messageGroup = document.createElement('div');
        messageGroup.style.marginBottom = '20px';
        
        const messageLabel = document.createElement('label');
        messageLabel.textContent = 'Mensaje:';
        messageLabel.style.display = 'block';
        messageLabel.style.marginBottom = '5px';
        messageLabel.style.color = mainPageData.textColor || '#000000';
        
        const messageInput = document.createElement('textarea');
        messageInput.required = true;
        messageInput.style.width = '100%';
        messageInput.style.padding = '8px';
        messageInput.style.borderRadius = '4px';
        messageInput.style.border = '1px solid #ccc';
        messageInput.style.minHeight = '100px';
        messageInput.style.resize = 'vertical';
        
        messageGroup.appendChild(messageLabel);
        messageGroup.appendChild(messageInput);
        contactForm.appendChild(messageGroup);
        
        // Botón Enviar
        const submitButton = document.createElement('button');
        submitButton.type = 'submit';
        submitButton.textContent = 'Enviar Mensaje';
        submitButton.style.backgroundColor = '#3b82f6';
        submitButton.style.color = 'white';
        submitButton.style.border = 'none';
        submitButton.style.padding = '10px 20px';
        submitButton.style.borderRadius = '4px';
        submitButton.style.cursor = 'pointer';
        submitButton.style.fontWeight = 'bold';
        submitButton.style.transition = 'background-color 0.3s';
        
        submitButton.addEventListener('mouseover', () => {
            submitButton.style.backgroundColor = '#2563eb';
        });
        
        submitButton.addEventListener('mouseout', () => {
            submitButton.style.backgroundColor = '#3b82f6';
        });
        
        contactForm.appendChild(submitButton);
        mainContent.appendChild(contactForm);
        newPage.appendChild(mainContent);

        // Footer
        const previewFooter = document.createElement('footer');
        previewFooter.style.backgroundColor = mainPageData.footer?.bgColor || '#f8f8f8';
        previewFooter.style.padding = mainPageData.footer?.padding || '15px';
        previewFooter.style.textAlign = 'center';
        previewFooter.style.marginTop = 'auto';
        previewFooter.textContent = mainPageData.footer?.text || '';
        previewFooter.style.color = mainPageData.footer?.textColor || '#000000';
        previewFooter.style.fontFamily = mainPageData.fontFamily || 'Arial, sans-serif';

        newPage.appendChild(previewFooter);

        preview.innerHTML = '';
        preview.appendChild(newPage);
    } catch (error) {
        console.error('Error al actualizar la vista previa:', error);
    }
}

// Función para guardar los datos de contacto
function saveContactData() {
    const contactData = {
        showMap: showMapCheckbox.checked,
        mapAddress: mapAddressInput.value.trim(),
        contactInfo: {}
    };
    
    contactCheckboxes.forEach(option => {
        const checkbox = document.getElementById(option.id);
        const input = document.getElementById(`${option.id}-text`);
        
        if (checkbox && input) {
            contactData.contactInfo[option.id] = {
                selected: checkbox.checked,
                text: input.value.trim()
            };
        }
    });
    
    localStorage.setItem('contactData', JSON.stringify(contactData));
}

// Función para cargar datos de contacto guardados
function loadContactData() {
    const savedData = localStorage.getItem('contactData');
    if (savedData) {
        const contactData = JSON.parse(savedData);
        
        showMapCheckbox.checked = contactData.showMap || false;
        mapAddressInput.value = contactData.mapAddress || '';
        
        contactCheckboxes.forEach(option => {
            const checkbox = document.getElementById(option.id);
            const input = document.getElementById(`${option.id}-text`);
            
            if (checkbox && input && contactData.contactInfo?.[option.id]) {
                checkbox.checked = contactData.contactInfo[option.id].selected;
                input.value = contactData.contactInfo[option.id].text || '';
            }
        });
    }
}

// Función para resetear el formulario
function resetForm() {
    contactCheckboxes.forEach(option => {
        const checkbox = document.getElementById(option.id);
        const input = document.getElementById(`${option.id}-text`);
        
        if (checkbox && input) {
            checkbox.checked = false;
            input.value = '';
        }
    });
    
    showMapCheckbox.checked = false;
    mapAddressInput.value = '';
    
    saveContactData();
    updatePreview();
    alert('El formulario de contacto ha sido reiniciado correctamente.');
}

// Función para guardar los datos y continuar
function saveAndContinue() {
    saveContactData();
    window.location.href = '../publish/publish.html';
}

// Configuración de eventos
resetBtn.addEventListener('click', () => {
    document.getElementById('reset-modal').style.display = 'flex';
});

document.getElementById('cancel-reset').addEventListener('click', () => {
    document.getElementById('reset-modal').style.display = 'none';
});

document.getElementById('confirm-reset').addEventListener('click', () => {
    resetForm();
    document.getElementById('reset-modal').style.display = 'none';
});

continueBtn.addEventListener('click', saveAndContinue);

// Inicialización
document.addEventListener('DOMContentLoaded', async () => {
    try {
        await openDatabase();
        initContactOptions();
        loadContactData();
        await updatePreview();
    } catch (error) {
        console.error('Error al cargar la página:', error);
    }
});

// Funciones de IndexedDB
function openDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);
        
        request.onerror = (event) => {
            console.error('Error al abrir la base de datos:', event.target.error);
            reject('Error al abrir la base de datos');
        };
        
        request.onsuccess = (event) => {
            db = event.target.result;
            resolve(db);
        };
        
        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'id' });
            }
        };
    });
}

function getImageFromDB(id) {
    return new Promise((resolve, reject) => {
        if (!db) {
            reject('La base de datos no está inicializada');
            return;
        }
        
        const tx = db.transaction(STORE_NAME, 'readonly');
        const store = tx.objectStore(STORE_NAME);
        const request = store.get(id);
        
        request.onsuccess = (event) => {
            resolve(event.target.result);
        };
        
        request.onerror = (event) => {
            console.error('Error al obtener la imagen:', event.target.error);
            reject('Error al obtener la imagen');
        };
    });
}
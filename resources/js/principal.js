// Elementos del DOM
const bgColorInput = document.getElementById('bg-color');
const logoInput = document.getElementById('logo');
const logoPositionInput = document.getElementById('logo-position');
const headerTextInput = document.getElementById('header-text');
const fontFamilyInput = document.getElementById('font-family');
const mainPhotoInput = document.getElementById('main-photo');
const footerTextInput = document.getElementById('footer-text');
const resetBtn = document.getElementById('reset-btn');
const continueBtn = document.getElementById('continue-btn');
const preview = document.getElementById('preview');
const photoTitleInput = document.getElementById('photo-title');
const photoDescriptionInput = document.getElementById('photo-description');
const descriptionAlignInput = document.getElementById('photo-description-align');
const headerBgColorInput = document.getElementById('header-bg-color');
const footerBgColorInput = document.getElementById('footer-bg-color');
const textColorInput = document.getElementById('text-color');
const headerTextColorInput = document.getElementById('header-text-color');
const footerTextColorInput = document.getElementById('footer-text-color');
const contactPage = localStorage.getItem('contactPage') === 'true';

// Nuevos elementos para el contenido intermedio
const contentTypeInput = document.getElementById('content-type');
const featureModuleOptions = document.getElementById('feature-module-options');
const videoOptions = document.getElementById('video-options');
const mapOptions = document.getElementById('map-options');
const videoUrlInput = document.getElementById('video-url');
const videoDescInput = document.getElementById('video-description');
const mapAddressInput = document.getElementById('map-address');
const mapDescInput = document.getElementById('map-description');

// Variables para IndexedDB
let db;
const DB_NAME = 'WebDesignDB';
const DB_VERSION = 1;
const STORE_NAME = 'images';

// Abrir o crear la base de datos IndexedDB
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

// Guardar una imagen en IndexedDB
function saveImageToDB(id, file) {
    return new Promise((resolve, reject) => {
        if (!db) {
            reject('La base de datos no está inicializada');
            return;
        }
        
        if (!file) {
            resolve(null);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (event) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            
            const imageData = {
                id: id,
                name: file.name,
                type: file.type,
                data: event.target.result,
                lastModified: file.lastModified
            };
            
            const request = store.put(imageData);
            
            request.onsuccess = () => {
                resolve({
                    id: id,
                    name: file.name
                });
            };
            
            request.onerror = (event) => {
                console.error('Error al guardar la imagen:', event.target.error);
                reject('Error al guardar la imagen');
            };
        };
        
        reader.onerror = (event) => {
            console.error('Error al leer el archivo:', event.target.error);
            reject('Error al leer el archivo');
        };
        
        reader.readAsDataURL(file);
    });
}

// Obtener una imagen de IndexedDB
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

// Función para obtener la extensión de un archivo
function getFileExtension(filename) {
    return filename.split('.').pop();
}

// Función para resetear el formulario
function resetForm() {
    bgColorInput.value = '#ffffff';
    logoInput.value = '';
    logoPositionInput.value = 'center';
    headerTextInput.value = '';
    fontFamilyInput.value = 'Arial, sans-serif';
    mainPhotoInput.value = '';
    footerTextInput.value = '';
    photoTitleInput.value = '';
    photoDescriptionInput.value = '';
    descriptionAlignInput.value = 'justify';
    headerBgColorInput.value = '#f8f8f8';
    footerBgColorInput.value = '#f8f8f8';
    textColorInput.value = '#000000';
    headerTextColorInput.value = '#000000';
    footerTextColorInput.value = '#000000';
    
    // Resetear nuevo contenido
    contentTypeInput.value = 'none';
    videoUrlInput.value = '';
    videoDescInput.value = '';
    mapAddressInput.value = '';
    mapDescInput.value = '';
    
    // Resetear módulo destacable
    document.querySelectorAll('.feature-text').forEach(input => {
        input.value = '';
    });
    document.querySelectorAll('.icon-select').forEach(select => {
        select.value = 'star';
    });
    
    // Ocultar todas las opciones
    document.querySelectorAll('.content-options').forEach(opt => {
        opt.style.display = 'none';
    });
}

// Función para extraer ID de video de YouTube/Vimeo
function extractVideoId(url) {
    if (!url) return null;
    
    // YouTube
    const youtubeRegExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const youtubeMatch = url.match(youtubeRegExp);
    if (youtubeMatch && youtubeMatch[2].length === 11) {
        return youtubeMatch[2];
    }
    
    // Vimeo
    const vimeoRegExp = /(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
    const vimeoMatch = url.match(vimeoRegExp);
    if (vimeoMatch && vimeoMatch[5]) {
        return vimeoMatch[5];
    }
    
    return null;
}

// Función para obtener SVG de iconos
function getIconSvg(iconName) {
    const icons = {
        star: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b82f6" width="48px" height="48px"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>`,
        shield: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b82f6" width="48px" height="48px"><path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3z"/></svg>`,
        trophy: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b82f6" width="48px" height="48px"><path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/></svg>`,
        lightbulb: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b82f6" width="48px" height="48px"><path d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7z"/></svg>`,
        heart: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b82f6" width="48px" height="48px"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`
    };
    return icons[iconName] || icons['star'];
}

// Función para actualizar la vista previa
function updatePreview() {
    // Crear la estructura base
    const newPage = document.createElement('div');
    newPage.style.display = 'flex';
    newPage.style.flexDirection = 'column';
    newPage.style.height = '100%';
    newPage.style.backgroundColor = bgColorInput.value;
    newPage.style.color = textColorInput.value;
    newPage.style.fontFamily = fontFamilyInput.value;

    // Header
    const previewHeader = document.createElement('header');
    previewHeader.style.backgroundColor = headerBgColorInput.value;
    previewHeader.style.padding = '15px';
    previewHeader.style.display = 'flex';
    previewHeader.style.alignItems = 'center';
    
    // Contenedor para logo y texto
    const headerContainer = document.createElement('div');
    headerContainer.style.display = 'flex';
    headerContainer.style.alignItems = 'center';
    headerContainer.style.gap = '20px';
    headerContainer.style.width = '100%';

    if (logoPositionInput.value === 'left') {
        // Logo a la izquierda, texto a la derecha
        headerContainer.style.flexDirection = 'row';
        headerContainer.style.justifyContent = 'space-between';
        previewHeader.style.paddingLeft = '30px';
        previewHeader.style.paddingRight = '30px';
    } else {
        // Logo y texto centrados (en columna)
        headerContainer.style.flexDirection = 'column';
        headerContainer.style.justifyContent = 'center';
        headerContainer.style.alignItems = 'center';
    }

    // Logo
    if (logoInput.files && logoInput.files[0]) {
        const logo = document.createElement('img');
        logo.src = URL.createObjectURL(logoInput.files[0]);
        logo.alt = 'Logotipo';
        logo.style.maxHeight = '60px';
        logo.style.width = 'auto';
        logo.style.objectFit = 'contain';
        headerContainer.appendChild(logo);
    }

    // Texto del header
    if (headerTextInput.value.trim()) {
        const headerText = document.createElement('div');
        headerText.textContent = headerTextInput.value.trim();
        headerText.style.color = headerTextColorInput.value;
        headerText.style.fontFamily = fontFamilyInput.value;
        headerText.style.fontWeight = 'bold';
        
        if (logoPositionInput.value === 'left') {
            headerText.style.textAlign = 'right';
            headerText.style.paddingRight = '20px';
        } else {
            headerText.style.textAlign = 'center';
        }
        
        headerContainer.appendChild(headerText);
    }

    previewHeader.appendChild(headerContainer);
    newPage.appendChild(previewHeader);

    // Verificar si hay página de contacto y añadir navbar con botones
    const contactPage = localStorage.getItem('contactPage') === 'true';
    if (contactPage) {
        const navBar = document.createElement('nav');
        navBar.style.backgroundColor = lightenColor(headerBgColorInput.value, 20);
        navBar.style.padding = '0px 0px';
        navBar.style.display = 'flex';
        navBar.style.justifyContent = 'center';
        navBar.style.gap = '10px';
        navBar.style.borderBottom = '1px solid rgba(0,0,0,0.1)';

        const homeButton = document.createElement('button');
        homeButton.textContent = 'Inicio';
        homeButton.style.padding = '8px 16px';
        homeButton.style.border = 'none';
        homeButton.style.background = 'none';
        homeButton.style.color = textColorInput.value;
        homeButton.style.cursor = 'pointer';
        homeButton.style.fontFamily = fontFamilyInput.value;
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
        contactButton.style.color = textColorInput.value;
        contactButton.style.cursor = 'pointer';
        contactButton.style.fontFamily = fontFamilyInput.value;
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
    mainContent.style.padding = '0px';
    mainContent.style.overflowY = 'auto';

    // Imagen principal
    if (mainPhotoInput.files && mainPhotoInput.files[0]) {
        const photoContainer = document.createElement('div');
        photoContainer.style.position = 'relative';
        photoContainer.style.marginBottom = '20px';

        const mainPhoto = document.createElement('img');
        mainPhoto.src = URL.createObjectURL(mainPhotoInput.files[0]);
        mainPhoto.alt = 'Foto principal';
        mainPhoto.style.width = '100%';
        mainPhoto.style.aspectRatio = '4 / 1';
        mainPhoto.style.objectFit = 'cover';
        photoContainer.appendChild(mainPhoto);

        // Título sobre la imagen
        if (photoTitleInput.value.trim()) {
            const photoTitle = document.createElement('h2');
            photoTitle.textContent = photoTitleInput.value.trim();
            photoTitle.style.position = 'absolute';
            photoTitle.style.top = '50%';
            photoTitle.style.left = '50%';
            photoTitle.style.transform = 'translate(-50%, -50%)';
            photoTitle.style.color = 'white';
            photoTitle.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
            photoTitle.style.padding = '10px 20px';
            photoTitle.style.borderRadius = '5px';
            photoTitle.style.fontSize = '24px';
            photoTitle.style.textAlign = 'center';
            photoTitle.style.maxWidth = '80%';
            photoContainer.appendChild(photoTitle);
        }

        mainContent.appendChild(photoContainer);
    }

    // Párrafo descriptivo
    if (photoDescriptionInput.value.trim()) {
        const description = document.createElement('p');
        description.textContent = photoDescriptionInput.value.trim();
        description.style.margin = '15px 0';
        description.style.padding = '0 30px';
        description.style.lineHeight = '1.6';
        description.style.textAlign = descriptionAlignInput.value;
        mainContent.appendChild(description);
    }

    // Contenido intermedio seleccionado
    const selectedContentType = contentTypeInput.value;
    
    if (selectedContentType !== 'none') {
        const contentContainer = document.createElement('div');
        contentContainer.style.margin = '20px 0';
        contentContainer.style.padding = '0 20px';
        
        if (selectedContentType === 'feature-module') {
            // Crear módulo destacable
            const featureModule = document.createElement('div');
            featureModule.style.display = 'flex';
            featureModule.style.justifyContent = 'space-between';
            featureModule.style.padding = '20px';
            featureModule.style.margin = '20px 0';
            featureModule.style.backgroundColor = '#f8fafc';
            featureModule.style.borderRadius = '8px';
            featureModule.style.flexWrap = 'nowrap';
            
            // Añadir las tres columnas siempre
            for (let i = 0; i < 3; i++) {
                const col = document.querySelectorAll('.feature-column')[i];
                const icon = col?.querySelector('.icon-select').value || 'star';
                const text = col?.querySelector('.feature-text').value || '';
                
                const featureItem = document.createElement('div');
                featureItem.style.flex = '1';
                featureItem.style.minWidth = '0';
                featureItem.style.padding = '0 15px';
                featureItem.style.textAlign = 'center';
                
                const iconElement = document.createElement('div');
                iconElement.innerHTML = getIconSvg(icon);
                iconElement.style.margin = '0 auto 10px';
                iconElement.style.width = '48px';
                iconElement.style.height = '48px';
                
                const textElement = document.createElement('p');
                textElement.textContent = text;
                textElement.style.marginTop = '10px';
                textElement.style.color = textColorInput.value;
                
                featureItem.appendChild(iconElement);
                featureItem.appendChild(textElement);
                featureModule.appendChild(featureItem);
            }
            
            contentContainer.appendChild(featureModule);
            
        } else if (selectedContentType === 'video') {
            // Crear reproductor de vídeo
            const videoUrl = videoUrlInput.value;
            const videoDesc = videoDescInput.value;
            
            if (videoUrl) {
                const videoId = extractVideoId(videoUrl);
                if (videoId) {
                    const videoWrapper = document.createElement('div');
                    videoWrapper.style.position = 'relative';
                    videoWrapper.style.paddingBottom = '56.25%';
                    videoWrapper.style.height = '0';
                    videoWrapper.style.margin = '20px 0';
                    videoWrapper.style.overflow = 'hidden';
                    videoWrapper.style.borderRadius = '8px';
                    
                    const videoIframe = document.createElement('iframe');
                    videoIframe.src = `https://www.youtube.com/embed/${videoId}`;
                    videoIframe.setAttribute('frameborder', '0');
                    videoIframe.setAttribute('allowfullscreen', '');
                    videoIframe.style.position = 'absolute';
                    videoIframe.style.top = '0';
                    videoIframe.style.left = '0';
                    videoIframe.style.width = '100%';
                    videoIframe.style.height = '100%';
                    
                    videoWrapper.appendChild(videoIframe);
                    contentContainer.appendChild(videoWrapper);
                }
            }
            
            if (videoDesc) {
                const descElement = document.createElement('p');
                descElement.textContent = videoDesc;
                descElement.style.marginTop = '10px';
                descElement.style.color = textColorInput.value;
                contentContainer.appendChild(descElement);
            }
            
        } else if (selectedContentType === 'map') {
            // Crear mapa
            const mapAddress = mapAddressInput.value;
            const mapDesc = mapDescInput.value;
            
            if (mapAddress) {
                const mapWrapper = document.createElement('div');
                mapWrapper.style.height = '300px';
                mapWrapper.style.margin = '20px 0';
                mapWrapper.style.borderRadius = '8px';
                mapWrapper.style.overflow = 'hidden';
                
                const mapIframe = document.createElement('iframe');
                mapIframe.src = `https://maps.google.com/maps?q=${encodeURIComponent(mapAddress)}&output=embed`;
                mapIframe.style.width = '100%';
                mapIframe.style.height = '100%';
                mapIframe.setAttribute('frameborder', '0');
                mapIframe.setAttribute('allowfullscreen', '');
                
                mapWrapper.appendChild(mapIframe);
                contentContainer.appendChild(mapWrapper);
            }
            
            if (mapDesc) {
                const descElement = document.createElement('p');
                descElement.textContent = mapDesc;
                descElement.style.marginTop = '10px';
                descElement.style.color = textColorInput.value;
                contentContainer.appendChild(descElement);
            }
        }
        
        if (contentContainer.children.length > 0) {
            mainContent.appendChild(contentContainer);
        }
    }

    newPage.appendChild(mainContent);

    // Footer
    const previewFooter = document.createElement('footer');
    previewFooter.style.backgroundColor = footerBgColorInput.value;
    previewFooter.style.padding = '15px';
    previewFooter.style.textAlign = 'center';
    previewFooter.style.marginTop = 'auto';
    previewFooter.textContent = footerTextInput.value.trim();
    previewFooter.style.color = footerTextColorInput.value;
    previewFooter.style.fontFamily = fontFamilyInput.value;

    newPage.appendChild(previewFooter);

    // Actualizar la vista previa
    preview.innerHTML = '';
    preview.appendChild(newPage);
}

// Función para aclarar un color (usada para el navbar)
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

// Función para guardar los datos y continuar
async function saveAndContinue() {
    try {
        // Abrir la base de datos
        await openDatabase();
        
        // Guardar las imágenes en IndexedDB
        const logoPromise = logoInput.files[0] ? 
            saveImageToDB('main-logo', logoInput.files[0]) : 
            Promise.resolve(null);
            
        const mainPhotoPromise = mainPhotoInput.files[0] ? 
            saveImageToDB('main-photo', mainPhotoInput.files[0]) : 
            Promise.resolve(null);
            
        const [logoData, mainPhotoData] = await Promise.all([logoPromise, mainPhotoPromise]);
        
        // Crear objeto con los datos del formulario
        const mainPageData = {
            // Datos básicos
            bgColor: bgColorInput.value,
            logo: logoData ? {
                id: logoData.id,
                name: logoData.name,
                position: logoPositionInput.value
            } : null,
            header: {
                text: headerTextInput.value,
                bgColor: headerBgColorInput.value,
                textColor: headerTextColorInput.value,
                padding: '15px', 
                paddingLeft: logoPositionInput.value === 'left' ? '30px' : '15px', 
                paddingRight: logoPositionInput.value === 'left' ? '30px' : '15px' 
            },
            mainPhoto: mainPhotoData ? {
                id: mainPhotoData.id,
                name: mainPhotoData.name
            } : null,
            photoContent: {
                title: photoTitleInput.value,
                description: photoDescriptionInput.value,
                align: descriptionAlignInput.value,
                descriptionPadding: '0 30px', 
                descriptionMargin: '15px 0' 
            },
            fontFamily: fontFamilyInput.value,
            textColor: textColorInput.value,
            footer: {
                text: footerTextInput.value,
                bgColor: footerBgColorInput.value,
                textColor: footerTextColorInput.value,
                padding: '15px'
            },
            
            // Contenido intermedio
            contentType: contentTypeInput.value,
            featureModule: contentTypeInput.value === 'feature-module' ? {
                columns: Array.from(document.querySelectorAll('.feature-column')).map(col => ({
                    icon: col.querySelector('.icon-select').value,
                    text: col.querySelector('.feature-text').value
                })),
                modulePadding: '20px', 
                moduleMargin: '20px 0' 
            } : null,
            video: contentTypeInput.value === 'video' ? {
                url: videoUrlInput.value,
                description: videoDescInput.value,
                videoMargin: '20px 0' 
            } : null,
            map: contentTypeInput.value === 'map' ? {
                address: mapAddressInput.value,
                description: mapDescInput.value,
                mapMargin: '20px 0' 
            } : null
        };

        // Guardar en localStorage
        localStorage.setItem('mainPageData', JSON.stringify(mainPageData));
        
        // Redirigir a la página de contacto
        setTimeout(() => {
            const redirectUrl = contactPage
            ? window.routes.contacto
            : window.routes.publicar;
        
        window.location.href = redirectUrl;
        }, 550); // 550 ms de margen

    } catch (error) {
        console.error('Error al guardar los datos:', error);
        alert('Hubo un error al guardar los datos. Por favor, inténtalo de nuevo.');
    }
}

// Manejador para mostrar/ocultar opciones según selección
contentTypeInput.addEventListener('change', function() {
    // Ocultar todas las opciones primero
    document.querySelectorAll('.content-options').forEach(opt => {
        opt.style.display = 'none';
    });
    
    // Mostrar las opciones seleccionadas
    if (this.value === 'feature-module') {
        featureModuleOptions.style.display = 'block';
    } else if (this.value === 'video') {
        videoOptions.style.display = 'block';
    } else if (this.value === 'map') {
        mapOptions.style.display = 'block';
    }
    
    updatePreview();
});

// Modal de reset
const resetButton = document.getElementById('reset-btn');
const resetModal = document.getElementById('reset-modal');
const cancelReset = document.getElementById('cancel-reset');
const confirmReset = document.getElementById('confirm-reset');

// Abrir modal
resetButton.addEventListener('click', () => {
    resetModal.style.display = 'flex';
});

// Cerrar modal
cancelReset.addEventListener('click', () => {
    resetModal.style.display = 'none';
});

resetModal.querySelector('.modal-overlay').addEventListener('click', () => {
    resetModal.style.display = 'none';
});

// Confirmar reset
confirmReset.addEventListener('click', () => {
    resetForm();
    updatePreview();
    resetModal.style.display = 'none';
    alert('El diseño ha sido reiniciado correctamente.');
});

// Event listener para el botón Continuar
continueBtn.addEventListener('click', saveAndContinue);

// Event listeners para actualizar la vista previa
const inputs = [
    bgColorInput, logoInput, logoPositionInput, headerTextInput,
    fontFamilyInput, mainPhotoInput, footerTextInput, photoTitleInput,
    photoDescriptionInput, descriptionAlignInput, headerBgColorInput,
    footerBgColorInput, textColorInput, headerTextColorInput, footerTextColorInput,
    contentTypeInput, videoUrlInput, videoDescInput, mapAddressInput, mapDescInput
];

inputs.forEach(input => {
    input.addEventListener('input', updatePreview);
    input.addEventListener('change', updatePreview);
});

// Event listeners para los selects de iconos y textos del módulo
document.querySelectorAll('.icon-select, .feature-text').forEach(element => {
    element.addEventListener('change', updatePreview);
});

// Función para cargar datos guardados
async function loadSavedData() {
    try {
        // Abrir la base de datos
        await openDatabase();
        
        const savedData = localStorage.getItem('mainPageData');
        if (savedData) {
            const data = JSON.parse(savedData);
            
            // Cargar datos básicos
            bgColorInput.value = data.bgColor || '#ffffff';
            if (data.logo) {
                logoPositionInput.value = data.logo.position || 'center';
                
                // Cargar logo desde IndexedDB si existe
                if (data.logo.id) {
                    const logoImage = await getImageFromDB(data.logo.id);
                    if (logoImage) {
                        // Crear un objeto File a partir de los datos guardados
                        const logoBlob = await fetch(logoImage.data).then(r => r.blob());
                        const logoFile = new File([logoBlob], logoImage.name, {
                            type: logoImage.type,
                            lastModified: logoImage.lastModified
                        });
                        
                        // Asignar el archivo al input
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(logoFile);
                        logoInput.files = dataTransfer.files;
                    }
                }
            }
            
            if (data.header) {
                headerTextInput.value = data.header.text || '';
                headerBgColorInput.value = data.header.bgColor || '#f8f8f8';
                headerTextColorInput.value = data.header.textColor || '#000000';
                // Los paddings se aplican automáticamente en updatePreview()
            }
            
            if (data.mainPhoto && data.mainPhoto.id) {
                // Cargar foto principal desde IndexedDB si existe
                const mainPhotoImage = await getImageFromDB(data.mainPhoto.id);
                if (mainPhotoImage) {
                    // Crear un objeto File a partir de los datos guardados
                    const mainPhotoBlob = await fetch(mainPhotoImage.data).then(r => r.blob());
                    const mainPhotoFile = new File([mainPhotoBlob], mainPhotoImage.name, {
                        type: mainPhotoImage.type,
                        lastModified: mainPhotoImage.lastModified
                    });
                    
                    // Asignar el archivo al input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(mainPhotoFile);
                    mainPhotoInput.files = dataTransfer.files;
                }
            }
            
            if (data.photoContent) {
                photoTitleInput.value = data.photoContent.title || '';
                photoDescriptionInput.value = data.photoContent.description || '';
                descriptionAlignInput.value = data.photoContent.align || 'justify';
                // Los paddings y margins se aplican automáticamente en updatePreview()
            }
            
            fontFamilyInput.value = data.fontFamily || 'Arial, sans-serif';
            textColorInput.value = data.textColor || '#000000';
            
            if (data.footer) {
                footerTextInput.value = data.footer.text || '';
                footerBgColorInput.value = data.footer.bgColor || '#f8f8f8';
                footerTextColorInput.value = data.footer.textColor || '#000000';
                // El padding se aplica automáticamente en updatePreview()
            }
            
            // Cargar contenido intermedio
            if (data.contentType) {
                contentTypeInput.value = data.contentType;
                
                // Mostrar las opciones correctas
                document.querySelectorAll('.content-options').forEach(opt => {
                    opt.style.display = 'none';
                });
                
                if (data.contentType === 'feature-module' && data.featureModule) {
                    featureModuleOptions.style.display = 'block';
                    data.featureModule.columns.forEach((col, index) => {
                        const columnElement = document.querySelectorAll('.feature-column')[index];
                        if (columnElement) {
                            columnElement.querySelector('.icon-select').value = col.icon || 'star';
                            columnElement.querySelector('.feature-text').value = col.text || '';
                        }
                    });
                } else if (data.contentType === 'video' && data.video) {
                    videoOptions.style.display = 'block';
                    videoUrlInput.value = data.video.url || '';
                    videoDescInput.value = data.video.description || '';
                } else if (data.contentType === 'map' && data.map) {
                    mapOptions.style.display = 'block';
                    mapAddressInput.value = data.map.address || '';
                    mapDescInput.value = data.map.description || '';
                }
            }
            
            // Actualizar la vista previa
            updatePreview();
        }
    } catch (error) {
        console.error('Error al cargar los datos guardados:', error);
    }
}

// Cargar datos al iniciar
document.addEventListener('DOMContentLoaded', loadSavedData);

// Inicializar vista previa
updatePreview();
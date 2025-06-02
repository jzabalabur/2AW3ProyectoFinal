document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PUBLICAR.JS CARGADO ===');
    
    // Verificar elementos del DOM
    const domainInput = document.getElementById('domain-input');
    const checkDomainBtn = document.getElementById('check-domain-btn');
    const domainResult = document.getElementById('domain-result');
    const publishBtn = document.getElementById('publish-btn');
    const saveDraftBtn = document.getElementById('save-draft-btn');

    console.log('Elementos encontrados:', {
        domainInput: !!domainInput,
        checkDomainBtn: !!checkDomainBtn,
        domainResult: !!domainResult,
        publishBtn: !!publishBtn,
        saveDraftBtn: !!saveDraftBtn
    });

    // Verificar datos en localStorage
    console.log('=== DATOS EN LOCALSTORAGE ===');
    console.log('webName:', localStorage.getItem('webName'));
    console.log('mainPageData:', localStorage.getItem('mainPageData') ? 'Presente' : 'Ausente');
    console.log('welcomeMessage:', localStorage.getItem('welcomeMessage'));
    console.log('welcomeData:', localStorage.getItem('welcomeData') ? 'Presente' : 'Ausente');
    console.log('contactPage:', localStorage.getItem('contactPage'));
    console.log('contactData:', localStorage.getItem('contactData') ? 'Presente' : 'Ausente');

    if (!saveDraftBtn) {
        console.error('ERROR: Botón guardar borrador no encontrado');
        return;
    }

    // Event listener para comprobar dominio
    if (checkDomainBtn) {
        checkDomainBtn.addEventListener('click', async function() {
            const domain = domainInput.value.trim();
            
            if (!isValidDomain(domain)) {
                showDomainResult('Formato de dominio inválido. Usa: ejemplo.mipagina.com', false);
                return;
            }

            showDomainResult('Comprobando disponibilidad...', true);
            
            try {
                const response = await fetch('/verificar-dominio', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: `url=${encodeURIComponent(domain)}`
                });

                if (!response.ok) throw new Error('Error en la respuesta del servidor');

                const data = await response.json();
                
                if (data.disponible) {
                    showDomainResult(`¡Dominio "${domain}" disponible!`, true);
                    publishBtn.classList.remove('hidden');
                } else {
                    showDomainResult(`El dominio "${domain}" ya está en uso`, false);
                    publishBtn.classList.add('hidden');
                }
            } catch (error) {
                showDomainResult('Error al comprobar el dominio. Inténtalo de nuevo.', false);
                console.error('Error:', error);
                publishBtn.classList.add('hidden');
            }
        });
    }

    // Event listener para guardar como borrador
    saveDraftBtn.addEventListener('click', async function() {
        console.log('=== INICIANDO GUARDADO DE BORRADOR ===');
        
        const originalText = saveDraftBtn.textContent;
        saveDraftBtn.disabled = true;
        saveDraftBtn.textContent = '💾 Guardando...';
        
        try {
            // 1. Verificar CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token no encontrado. Recarga la página.');
            }
            console.log('✓ CSRF token encontrado');
            
            // 2. Verificar datos en localStorage
            const mainPageData = localStorage.getItem('mainPageData');
            const welcomeData = localStorage.getItem('welcomeData');
            const contactData = localStorage.getItem('contactData');
            const webName = localStorage.getItem('webName');
            const welcomeEnabled = localStorage.getItem('welcomeMessage') === 'true';
            const contactEnabled = localStorage.getItem('contactPage') === 'true';
            
            console.log('=== DATOS RECOLECTADOS ===');
            console.log('webName:', webName);
            console.log('mainPageData:', mainPageData ? 'Presente (' + mainPageData.length + ' chars)' : 'Ausente');
            console.log('welcomeEnabled:', welcomeEnabled);
            console.log('welcomeData:', welcomeData ? 'Presente (' + welcomeData.length + ' chars)' : 'Ausente');
            console.log('contactEnabled:', contactEnabled);
            console.log('contactData:', contactData ? 'Presente (' + contactData.length + ' chars)' : 'Ausente');
            
            // Validaciones
            if (!mainPageData) {
                throw new Error('No hay datos de la página principal. Completa al menos la página principal antes de guardar.');
            }
            
            if (!webName || webName.trim() === '') {
                throw new Error('No hay nombre para la web. Vuelve al primer paso y asigna un nombre.');
            }
            
            console.log('✓ Validaciones pasadas');
            
            // 3. Verificar autenticación
            console.log('Verificando autenticación...');
            const userCheck = await fetch('/current-user-id', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                },
                credentials: 'include'
            });

            if (!userCheck.ok) {
                console.error('Error en verificación de usuario:', userCheck.status, userCheck.statusText);
                throw new Error('Usuario no autenticado. Por favor, inicia sesión.');
            }

            const userData = await userCheck.json();
            console.log('Datos de usuario:', userData);
            
            if (!userData.userId) {
                throw new Error('No se pudo obtener el ID del usuario');
            }
            console.log('✓ Usuario autenticado, ID:', userData.userId);
            
            // 4. Preparar FormData
            console.log('Preparando datos para envío...');
            const formData = new FormData();
            formData.append('user_id', userData.userId);
            formData.append('name', webName.trim());
            formData.append('mainPageData', mainPageData);
            
            // Solo incluir datos opcionales si están habilitados Y tienen datos
            if (welcomeEnabled && welcomeData) {
                console.log('→ Incluyendo datos de bienvenida');
                formData.append('welcomeData', welcomeData);
            }
            
            if (contactEnabled && contactData) {
                console.log('→ Incluyendo datos de contacto');
                formData.append('contactData', contactData);
            }

            // 5. Procesar imágenes de IndexedDB
            console.log('Procesando imágenes...');
            try {
                const db = await openIDB();
                const imageKeys = ['main-logo', 'main-photo', 'logoBienvenida', 'background'];
                const images = {};
                
                for (const key of imageKeys) {
                    try {
                        const image = await getImageFromDB(db, key);
                        if (image && image.data) {
                            // Verificar que la imagen tiene el formato correcto
                            if (image.data.includes('data:image/')) {
                                images[key] = image.data.split(',')[1]; // Remover prefijo base64
                                console.log(`→ Imagen ${key} procesada`);
                            }
                        }
                    } catch (e) {
                        console.warn(`No se encontró imagen ${key}:`, e.message);
                    }
                }
                
                if (Object.keys(images).length > 0) {
                    formData.append('images', JSON.stringify(images));
                    console.log('→ Imágenes añadidas:', Object.keys(images));
                } else {
                    console.log('→ No se encontraron imágenes');
                }
            } catch (dbError) {
                console.warn('Error al acceder a IndexedDB:', dbError);
                // Continuar sin imágenes
            }

            // 6. Enviar al servidor
            console.log('=== ENVIANDO AL SERVIDOR ===');
            const response = await fetch('/guardar-borrador', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include',
                body: formData
            });

            console.log('Respuesta del servidor:', response.status, response.statusText);

            // Manejar errores específicos
            if (response.status === 401) {
                throw new Error('No autorizado. Por favor, inicia sesión nuevamente.');
            }
            
            if (response.status === 419) {
                throw new Error('Token CSRF expirado. Por favor, recarga la página.');
            }
            
            if (response.status === 422) {
                const errorData = await response.json();
                throw new Error('Datos inválidos: ' + (errorData.message || JSON.stringify(errorData.errors)));
            }

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error response body:', errorText);
                throw new Error(`Error del servidor (${response.status}): ${response.statusText}`);
            }

            const data = await response.json();
            console.log('=== RESPUESTA EXITOSA ===');
            console.log(data);
            
            if (data.success) {
                console.log('✓ Borrador guardado exitosamente');
                showDraftSavedModal();
            } else {
                throw new Error(data.message || 'Error desconocido al guardar borrador');
            }
            
        } catch (error) {
            console.error('=== ERROR EN GUARDADO ===');
            console.error(error);
            
            // Mostrar error específico al usuario
            let errorMessage = 'Error al guardar: ' + error.message;
            
            if (error.message.includes('autenticado') || error.message.includes('autorizado')) {
                errorMessage += '\n\nSerás redirigido a la página de login.';
                setTimeout(() => {
                    window.location.href = '/login';
                }, 3000);
            }
            
            alert(errorMessage);
            
        } finally {
            // Restaurar botón
            saveDraftBtn.disabled = false;
            saveDraftBtn.textContent = originalText;
            console.log('=== PROCESO FINALIZADO ===');
        }
    });

    // Event listener para publicar
    if (publishBtn) {
        publishBtn.addEventListener('click', async function() {
            const domain = domainInput.value.trim();
            const originalText = publishBtn.textContent;
            publishBtn.disabled = true;
            publishBtn.textContent = '🚀 Publicando...';
            
            try {
                // Usar la misma lógica que guardar borrador pero con dominio
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token no encontrado. Recarga la página.');
                }

                const userCheck = await fetch('/current-user-id', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    },
                    credentials: 'include'
                });

                if (!userCheck.ok) {
                    throw new Error('Usuario no autenticado');
                }

                const userData = await userCheck.json();
                const userId = userData.userId;

                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('url', domain);
                formData.append('mainPageData', localStorage.getItem('mainPageData'));
                
                if (localStorage.getItem('welcomeMessage') === 'true') {
                    const welcomeData = localStorage.getItem('welcomeData');
                    if (welcomeData) {
                        formData.append('welcomeData', welcomeData);
                    }
                }
                
                if (localStorage.getItem('contactPage') === 'true') {
                    const contactData = localStorage.getItem('contactData');
                    if (contactData) {
                        formData.append('contactData', contactData);
                    }
                }

                // Recoger imágenes
                const db = await openIDB();
                const imageKeys = ['main-logo', 'main-photo', 'logoBienvenida', 'background'];
                const images = {};
                
                for (const key of imageKeys) {
                    try {
                        const image = await getImageFromDB(db, key);
                        if (image && image.data && image.data.includes('data:image/')) {
                            images[key] = image.data.split(',')[1];
                        }
                    } catch (e) {
                        console.warn(`No se encontró imagen con clave ${key}`);
                    }
                }
                
                if (Object.keys(images).length > 0) {
                    formData.append('images', JSON.stringify(images));
                }

                const response = await fetch('/publicar-pagina', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include',
                    body: formData
                });

                if (response.status === 401) {
                    throw new Error('No autorizado. Por favor, inicia sesión nuevamente.');
                }

                const data = await response.json();
                
                if (data.success) {
                    alert('¡Página publicada con éxito!');
                    setTimeout(() => {
                            window.location.href = '/perfil';
                    }, 3000);
                } else {
                    throw new Error(data.message || 'Error al publicar');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
                
                if (error.message.includes('autenticado') || error.message.includes('autorizado')) {
                    window.location.href = '/login';
                }
            } finally {
                publishBtn.disabled = false;
                publishBtn.textContent = originalText;
            }
        });
    }

    // Funciones auxiliares
    function isValidDomain(domain) {
        const domainRegex = /^(?!:\/\/)([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.[a-zA-Z]{2,11}?$/;
        return domainRegex.test(domain);
    }

    function showDomainResult(message, isSuccess) {
        if (domainResult) {
            domainResult.textContent = message;
            domainResult.className = `domain-result ${isSuccess ? 'success' : 'error'}`;
            domainResult.classList.remove('hidden');
        }
    }

    function showDraftSavedModal() {
        let modal = document.getElementById('draft-saved-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'draft-saved-modal';
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
                    background: #1f2937;
                    color: white;
                    padding: 2rem;
                    border-radius: 12px;
                    max-width: 500px;
                    width: 90%;
                    z-index: 1000;
                ">
                    <h3 style="color: #10b981; margin-bottom: 1rem; font-size: 1.5rem;">✅ ¡Web guardada exitosamente!</h3>
                    <p style="margin-bottom: 1.5rem;">Tu web se ha guardado como borrador en tu perfil. Puedes editarla cuando quieras.</p>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button id="continue-designing" style="
                            padding: 0.75rem 1.5rem;
                            border: none;
                            border-radius: 6px;
                            font-weight: 500;
                            cursor: pointer;
                            background: #6b7280;
                            color: white;
                        ">Continuar diseñando</button>
                        <button id="go-to-profile" style="
                            padding: 0.75rem 1.5rem;
                            border: none;
                            border-radius: 6px;
                            font-weight: 500;
                            cursor: pointer;
                            background: #10b981;
                            color: white;
                        ">Ir al perfil</button>
                    </div>
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
            
            // Event listeners para los botones
            document.getElementById('continue-designing').addEventListener('click', () => {
                modal.style.display = 'none';
            });
            
            document.getElementById('go-to-profile').addEventListener('click', () => {
                window.location.href = '/perfil';
            });
        }
        
        modal.style.display = 'flex';
    }

    // Funciones para IndexedDB
    function openIDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('WebDesignDB', 1);
            
            request.onerror = () => reject(new Error('Error al abrir IndexedDB'));
            request.onsuccess = (event) => resolve(event.target.result);
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains('images')) {
                    db.createObjectStore('images', { keyPath: 'id' });
                }
            };
        });
    }

    function getImageFromDB(db, id) {
        return new Promise((resolve, reject) => {
            try {
                const tx = db.transaction('images', 'readonly');
                const store = tx.objectStore('images');
                const request = store.get(id);
                
                request.onsuccess = (event) => {
                    const result = event.target.result;
                    if (result) {
                        resolve(result);
                    } else {
                        reject(new Error(`Imagen ${id} no encontrada`));
                    }
                };
                request.onerror = () => reject(new Error(`Error al obtener imagen ${id}`));
            } catch (error) {
                reject(error);
            }
        });
    }

    // Log final
    console.log('=== SCRIPT PUBLICAR.JS INICIALIZADO ===');
});
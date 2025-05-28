document.addEventListener('DOMContentLoaded', function() {
    const domainInput = document.getElementById('domain-input');
    const checkDomainBtn = document.getElementById('check-domain-btn');
    const domainResult = document.getElementById('domain-result');
    const publishBtn = document.getElementById('publish-btn');

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

publishBtn.addEventListener('click', async function() {
    const domain = domainInput.value.trim();
    
    try {
        // Verificar que hay un usuario autenticado
        const userCheck = await fetch('/current-user-id', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include' // Importante para incluir cookies de sesión
        });

        if (!userCheck.ok) {
            throw new Error('Usuario no autenticado');
        }

        const userData = await userCheck.json();
        const userId = userData.userId;

        // Recoger todos los datos necesarios
        const publishData = {
            user_id: userId,
            url: domain,
            mainPageData: localStorage.getItem('mainPageData'),
            welcomeData: localStorage.getItem('welcomeMessage') === 'true' ? 
                localStorage.getItem('welcomeData') : null,
            contactData: localStorage.getItem('contactPage') === 'true' ? 
                localStorage.getItem('contactData') : null,
            images: {}
        };

        // Abrir IndexedDB y recoger imágenes
        const db = await openIDB();
        const imageKeys = ['main-logo', 'main-photo', 'logoBienvenida', 'background'];
        
        for (const key of imageKeys) {
            try {
                const image = await getImageFromDB(db, key);
                if (image) {
                    publishData.images[key] = image.data.split(',')[1];
                }
            } catch (e) {
                console.warn(`No se encontró imagen con clave ${key}`);
            }
        }

        // Enviar datos al servidor con autenticación
        const response = await fetch('/publicar-pagina', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include', // Incluye cookies de sesión
            body: JSON.stringify(publishData)
        });

        if (response.status === 401) {
            throw new Error('No autorizado. Por favor, inicia sesión nuevamente.');
        }

        const data = await response.json();
        
        if (data.success) {
            alert('¡Página publicada con éxito!');
            window.location.href = data.url;
        } else {
            alert('Error al publicar: ' + (data.message || 'Inténtalo de nuevo'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error: ' + error.message);
        
        // Redirigir a login si no está autenticado
        if (error.message.includes('autenticado') || error.message.includes('autorizado')) {
            window.location.href = '/login';
        }
    }
});

    function isValidDomain(domain) {
        const domainRegex = /^(?!:\/\/)([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.[a-zA-Z]{2,11}?$/;
        return domainRegex.test(domain);
    }

    function showDomainResult(message, isSuccess) {
        domainResult.textContent = message;
        domainResult.className = `domain-result ${isSuccess ? 'success' : 'error'}`;
        domainResult.classList.remove('hidden');
    }

    // Funciones para IndexedDB
    function openIDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('WebDesignDB', 1);
            
            request.onerror = () => reject('Error al abrir la base de datos');
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
            const tx = db.transaction('images', 'readonly');
            const store = tx.objectStore('images');
            const request = store.get(id);
            
            request.onsuccess = (event) => resolve(event.target.result);
            request.onerror = () => reject('Error al obtener la imagen');
        });
    }
});
 document.addEventListener('DOMContentLoaded', function() {
            const domainInput = document.getElementById('domain-input');
            const checkDomainBtn = document.getElementById('check-domain-btn');
            const domainResult = document.getElementById('domain-result');
            const publishBtn = document.getElementById('publish-btn');

            checkDomainBtn.addEventListener('click', async function() {
            let domain = domainInput.value.trim();
                if (!domain.startsWith('www.')) {
                    domain = 'www.' + domain;
                }
                
                // Validar formato del dominio
                if (!isValidDomain(domain)) {
                    showDomainResult('Formato de dominio inválido. Ejemplo: mipagina.com', false);
                    return;
                }

                // Mostrar estado de carga
                showDomainResult('Comprobando disponibilidad...', true);
                
                try {
                    // Llamada al controlador web de Laravel
                    const response = await fetch('/verificar-url', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: `url=${encodeURIComponent(domain)}`
                    });

                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }

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
                    const response = await fetch('/publicar-pagina', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: `url=${encodeURIComponent(domain)}`
                    });

                    const data = await response.json();
                    
                    if (data.exito) {
                        alert('¡Página publicada con éxito!');
                        // Redirigir a la página de éxito o dashboard
                        window.location.href = '/pagina-publicada';
                    } else {
                        alert('Error al publicar: ' + (data.mensaje || 'Inténtalo de nuevo'));
                    }
                } catch (error) {
                    alert('Error al publicar la página');
                    console.error('Error:', error);
                }
            });

            function isValidDomain(domain) {
                // Expresión regular para validar dominio sin http/https
                const domainRegex = /^(?!:\/\/)([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
                return domainRegex.test(domain);
            }

            function showDomainResult(message, isSuccess) {
                domainResult.textContent = message;
                domainResult.className = `domain-result ${isSuccess ? 'success' : 'error'}`;
                domainResult.classList.remove('hidden');
            }
        });
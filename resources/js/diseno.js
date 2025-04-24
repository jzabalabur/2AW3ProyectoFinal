function proceedToNextStep() {
    console.log("HOLA");
    const welcomeMessage = document.getElementById('welcome-message').checked;
    const contactPage = document.getElementById('contact-page').checked;


    // Guardar de múltiples formas para redundancia
    localStorage.setItem('welcomeMessage', welcomeMessage.toString());
    localStorage.setItem('contactPage', contactPage.toString());
    
    sessionStorage.setItem('welcomeMessage', welcomeMessage.toString());
    sessionStorage.setItem('contactPage', contactPage.toString());

    console.log('Datos guardados:', {
        localStorage: {
            welcomeMessage: localStorage.getItem('welcomeMessage'),
            contactPage: localStorage.getItem('contactPage'),
        },
        sessionStorage: {
            welcomeMessage: sessionStorage.getItem('welcomeMessage'),
            contactPage: sessionStorage.getItem('contactPage'),
        }
    });

    // Redirigir con parámetro de tiempo para evitar caché
    setTimeout(() => {
        const redirectUrl = welcomeMessage
        ? window.routes.bienvenida
        : window.routes.principal;
    
    window.location.href = redirectUrl;


    }, 550);
}
document.getElementById('continuar').addEventListener('click', proceedToNextStep);

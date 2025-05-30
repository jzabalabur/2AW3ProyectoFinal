import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/diseno.js',
                'resources/js/bienvenida.js',
                'resources/js/bootstrap.js',
                'resources/js/contacto.js',
                'resources/js/principal.js',
                'resources/js/publicar.js',
                'resources/css/bienvenida.css',
                'resources/css/cliente.css',
                'resources/css/contacto.css',
                'resources/css/diseno.css',
                'resources/css/perfil.css',
                'resources/css/principal.css',
                'resources/css/publicar.css',
                'resources/css/welcome.css',


            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',  // Permite acceso desde cualquier IP
        port: 5174,
        cors: true,
        hmr: {
            host: '192.168.99.100',  // Tu IP
            port: 5174
        }
    }

});

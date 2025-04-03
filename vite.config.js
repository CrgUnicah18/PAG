import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: 'pag.test', // Tu dominio personalizado
        port: 5173, // Puerto que Vite usará
        https: false, // Cambia a true si usas HTTPS
    },
});

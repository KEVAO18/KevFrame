// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // Archivos de entrada de tu aplicación
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            // Genera el manifest en el directorio correcto
            publicDirectory: 'public',
            buildDirectory: 'build',
            // Refresca la página cuando cambies archivos PHP
            refresh: true,
        }),
    ],
});
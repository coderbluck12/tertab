import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Add this section for XAMPP path
            server: {
                host: true,
                hmr: {
                    host: 'localhost'
                },
            },
        }),
    ],
});
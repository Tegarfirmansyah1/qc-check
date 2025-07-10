import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // 👇 TAMBAHKAN BLOK INI
    server: {
        host: '0.0.0.0', // Memungkinkan Vite diakses dari jaringan
        hmr: {
            host: 'localhost',
        },
    },
    // END TAMBAHAN

    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

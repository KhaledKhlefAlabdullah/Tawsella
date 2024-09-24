import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'public/scss/material-dashboard.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});

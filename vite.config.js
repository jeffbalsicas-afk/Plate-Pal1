import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const devServerOrigin = process.env.VITE_DEV_SERVER_ORIGIN;

export default defineConfig({
    plugins: [
        tailwindcss(), // Moved to the top
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: process.env.VITE_HOST || undefined,
        origin: devServerOrigin || undefined,
    },
    // The server watch settings are fine, but Tailwind v4
    // handles its own watching of template files automatically.
});

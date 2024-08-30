import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/sidebarAdmin.css',
                'resources/css/formulaire.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/filterUsers.js',
                'resources/js/adminTabs.js',
                'resources/js/calendar.js',
                'resources/js/dashboard.js',

            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            external: ['tippy.js'],
        },
    },
});

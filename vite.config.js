import laravel from 'laravel-vite-plugin'
import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

export default defineConfig({
    plugins: [
        svelte(),
        laravel({
            input: ['resources/js/app.js', 'resources/js/ui.js'],
            buildDirectory: 'vendor/koboi',
        }),
    ],
    resolve: {
        conditions: ['browser'],
    },
    test: {
        environment: 'jsdom',
        globals: false,
        setupFiles: ['./resources/js/__tests__/setup.js'],
        include: ['resources/js/**/*.spec.js'],
    },
})
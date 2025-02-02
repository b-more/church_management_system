import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.jsx',  // Changed from app.js to app.jsx
            ],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    optimizeDeps: {
        include: ['react', 'react-dom'],
    },
    esbuild: {
        loader: 'jsx',  // Add this to handle JSX
        include: /\.[jt]sx?$/,  // Add this to handle JSX files
    },
});

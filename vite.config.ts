import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
    build: {
        lib: {
            entry: resolve(__dirname, 'resources/js/index.ts'),
            name: 'AcceladeAI',
            fileName: 'accelade-ai',
            formats: ['iife', 'es'] as const,
        },
        outDir: 'dist',
        emptyOutDir: true,
        rollupOptions: {
            external: [],
            output: [
                {
                    format: 'iife' as const,
                    entryFileNames: 'accelade-ai.js',
                    assetFileNames: '[name].[ext]',
                    inlineDynamicImports: true,
                    name: 'AcceladeAI',
                    exports: 'named' as const,
                },
                {
                    format: 'es' as const,
                    entryFileNames: 'accelade-ai.esm.js',
                    assetFileNames: '[name].[ext]',
                    inlineDynamicImports: true,
                    exports: 'named' as const,
                },
            ],
        },
        sourcemap: true,
        minify: 'terser' as const,
        terserOptions: {
            compress: {
                drop_console: false,
            },
        },
        target: 'es2020',
        cssCodeSplit: false,
    },

    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },

    define: {
        'process.env.NODE_ENV': JSON.stringify('production'),
    },
});
